<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Repositories\Odeme\KuveytPosOdeme;
use Mews\Pos\PosInterface;
use Mews\Pos\Factory\PosFactory;
use Mews\Pos\Entity\Account\KuveytPosAccount;
use Illuminate\Support\Facades\Log;

class QueryKuveytPosStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kuveytpos:status 
                            {--order_id= : Order ID (database ID)}
                            {--order_number= : Order Number (MerchantOrderId)}
                            {--remote_order_id= : Remote Order ID from Kuveyt POS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kuveyt POS sipariş durumu sorgulama';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->option('order_id');
        $orderNumber = $this->option('order_number');
        $remoteOrderId = $this->option('remote_order_id');

        // Order bul
        $order = null;
        if ($orderId) {
            $order = Order::find($orderId);
        } elseif ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
        }

        if (!$order && !$remoteOrderId) {
            $this->error('Order bulunamadı veya remote_order_id belirtilmedi.');
            return 1;
        }

        // Kuveyt POS gateway oluştur
        $account = \Mews\Pos\Factory\AccountFactory::createKuveytPosAccount(
            'kuveytpos',
            config('services.kuveytpos.merchant_id'),
            config('services.kuveytpos.user_name'),
            config('services.kuveytpos.customer_id'),
            config('services.kuveytpos.password'),
            PosInterface::MODEL_3D_SECURE,
            PosInterface::LANG_TR
        );

        $isTestMode = config('services.kuveytpos.test_mode', false) || app()->environment() == 'local';
        
        $testConfig = config("pos_test.banks.kuveytpos");
        $prodConfig = config("pos_production.banks.kuveytpos");
        
        if ($isTestMode && $testConfig) {
            $config = $testConfig;
        } elseif (!$isTestMode && $prodConfig) {
            $config = $prodConfig;
        } else {
            $config = [
                'name' => 'kuveyt-pos',
                'class' => \Mews\Pos\Gateways\KuveytPos::class,
                'gateway_endpoints' => [
                    'payment_api' => $isTestMode 
                        ? 'https://boatest.kuveytturk.com.tr/boa.virtualpos.services/Home'
                        : 'https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home',
                    'query_api' => $isTestMode 
                        ? 'https://boatest.kuveytturk.com.tr/BOA.Integration.WCFService/BOA.Integration.VirtualPos/VirtualPosService.svc?wsdl'
                        : 'https://boa.kuveytturk.com.tr/BOA.Integration.WCFService/BOA.Integration.VirtualPos/VirtualPosService.svc?wsdl',
                ],
                'gateway_configs' => [
                    'test_mode' => $isTestMode,
                ]
            ];
        }

        $banksConfig = [
            'banks' => [
                'kuveytpos' => $config
            ]
        ];

        $eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
        $guzzleClient = new \GuzzleHttp\Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => false,
            'http_errors' => false,
        ]);
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $httpClient = new \Mews\Pos\Client\HttpClient($guzzleClient, $psr17Factory, $psr17Factory);

        $logger = new \Monolog\Logger('kuveytpos_status');
        $logHandler = new \Monolog\Handler\StreamHandler(storage_path('logs/kuveytpos_status.log'), \Monolog\Logger::DEBUG);
        $logger->pushHandler($logHandler);

        $pos = PosFactory::createPosGateway($account, $banksConfig, $eventDispatcher, $httpClient, $logger);

        // ÖNEMLI: Status sorgusu için TransactionStep parametresini ekle
        // SOAP request'inde TransactionStep property'si gerekiyor
        $statusListener = function (\Mews\Pos\Event\RequestDataPreparedEvent $event) {
            if ($event->getBank() === 'kuveytpos' && 
                $event->getTxType() === PosInterface::TX_TYPE_STATUS) {
                
                $requestData = $event->getRequestData();
                
                // TransactionStep parametresini ekle (SOAP için gerekli)
                // 0 = Tüm işlemler, 1 = Sadece başarılı işlemler, 2 = Sadece başarısız işlemler
                $requestData['TransactionStep'] = 0; // Tüm işlemler
                
                Log::info('Kuveyt POS: TransactionStep eklendi (Status sorgusu)', [
                    'transaction_step' => $requestData['TransactionStep']
                ]);
                
                $event->setRequestData($requestData);
            }
        };
        
        $eventDispatcher->addListener(\Mews\Pos\Event\RequestDataPreparedEvent::class, $statusListener);

        // Status sorgusu için order data hazırla
        $merchantOrderId = $order ? $order->order_number : $orderNumber;
        
        if (!$merchantOrderId) {
            $this->error('MerchantOrderId bulunamadı.');
            return 1;
        }

        $statusOrderData = [
            'id' => $merchantOrderId,
            'remote_order_id' => $remoteOrderId ?? 0, // 0 = tüm işlemleri getir
            'currency' => PosInterface::CURRENCY_TRY,
            'start_date' => \Carbon\Carbon::now()->subDays(7), // Son 7 gün
            'end_date' => \Carbon\Carbon::now(),
        ];

        $this->info("Kuveyt POS Status Sorgusu Başlatılıyor...");
        $this->info("MerchantOrderId: {$merchantOrderId}");
        if ($remoteOrderId) {
            $this->info("Remote OrderId: {$remoteOrderId}");
        }
        $this->line("");

        try {
            // Status sorgusu yap (status metodu transaction type'ı kendisi belirliyor)
            $pos->status($statusOrderData);

            $response = $pos->getResponse();
            $isSuccess = $pos->isSuccess();

            // Response'u her zaman göster (başarılı veya başarısız olsun)
            if ($isSuccess) {
                $this->info("✅ Status sorgusu başarılı!");
            } else {
                $this->error("❌ Status sorgusu başarısız!");
            }
            $this->line("");
            
            // Response'u formatla ve göster
            $this->displayResponse($response);

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Hata oluştu: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
            
            Log::error('Kuveyt POS status sorgusu hatası', [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'remote_order_id' => $remoteOrderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }
    }

    /**
     * Response'u formatla ve göster
     */
    protected function displayResponse($response)
    {
        // Mews POS'un parse ettiği response'u göster
        if (isset($response['status'])) {
            $this->info("=== Ödeme Durumu ===");
            $statusMap = [
                'approved' => '✅ Onaylandı',
                'declined' => '❌ Reddedildi',
                'error' => '⚠️ Hata',
            ];
            $this->info("Durum: " . ($statusMap[$response['status']] ?? $response['status']));
            $this->line("");
        }

        // OrderContract bilgilerini göster (Status sorgusu için)
        if (isset($response['all']['GetMerchantOrderDetailResult']['Value']['OrderContract'])) {
            $orderContract = $response['all']['GetMerchantOrderDetailResult']['Value']['OrderContract'];
            
            $this->info("=== Sipariş Detayları ===");
            
            $statusMap = [
                1 => 'Başarılı',
                2 => 'İptal Edildi',
                3 => 'İade Edildi',
                4 => 'Beklemede',
            ];
            
            $transactionStatusMap = [
                1 => 'Başarılı',
                2 => 'İptal',
                3 => 'İade',
                4 => 'Beklemede',
            ];
            
            $this->table(
                ['Alan', 'Değer'],
                [
                    ['OrderId', $orderContract['OrderId'] ?? 'N/A'],
                    ['MerchantOrderId', $orderContract['MerchantOrderId'] ?? 'N/A'],
                    ['MerchantId', $orderContract['MerchantId'] ?? 'N/A'],
                    ['Tutar', ($orderContract['FirstAmount'] ?? 0) . ' TL'],
                    ['İptal Tutarı', ($orderContract['CancelAmount'] ?? 0) . ' TL'],
                    ['İade Tutarı', ($orderContract['DrawbackAmount'] ?? 0) . ' TL'],
                    ['Kapanan Tutar', ($orderContract['ClosedAmount'] ?? 0) . ' TL'],
                    ['OrderStatus', ($orderContract['OrderStatus'] ?? 'N/A') . ' - ' . ($statusMap[$orderContract['OrderStatus'] ?? 0] ?? 'Bilinmiyor')],
                    ['LastOrderStatus', ($orderContract['LastOrderStatus'] ?? 'N/A') . ' - ' . ($statusMap[$orderContract['LastOrderStatus'] ?? 0] ?? 'Bilinmiyor')],
                    ['TransactionStatus', ($orderContract['TransactionStatus'] ?? 'N/A') . ' - ' . ($transactionStatusMap[$orderContract['TransactionStatus'] ?? 0] ?? 'Bilinmiyor')],
                    ['ResponseCode', $orderContract['ResponseCode'] ?? 'N/A'],
                    ['ResponseExplain', $orderContract['ResponseExplain'] ?? 'N/A'],
                    ['ProvNumber (Auth Code)', $orderContract['ProvNumber'] ?? 'N/A'],
                    ['RRN', $orderContract['RRN'] ?? 'N/A'],
                    ['Stan', $orderContract['Stan'] ?? 'N/A'],
                    ['BatchId', $orderContract['BatchId'] ?? 'N/A'],
                    ['Kart Tipi', $orderContract['CardType'] ?? 'N/A'],
                    ['Maskelenmiş Kart No', $orderContract['CardNumber'] ?? 'N/A'],
                    ['Taksit Sayısı', $orderContract['InstallmentCount'] ?? 'N/A'],
                    ['3D Secure', $orderContract['TransactionSecurity'] == 3 ? 'Evet' : 'Hayır'],
                    ['Sipariş Tarihi', $orderContract['OrderDate'] ?? 'N/A'],
                    ['IdentityTaxNumber', $orderContract['IdentityTaxNumber'] ?? 'N/A'],
                ]
            );
            
            $this->line("");
        }

        // Mews POS'un parse ettiği standart response alanları
        if (isset($response['order_id'])) {
            $this->info("=== Parse Edilmiş Bilgiler ===");
            $this->table(
                ['Alan', 'Değer'],
                [
                    ['Order ID', $response['order_id'] ?? 'N/A'],
                    ['Remote Order ID', $response['remote_order_id'] ?? 'N/A'],
                    ['Status', $response['status'] ?? 'N/A'],
                    ['Proc Return Code', $response['proc_return_code'] ?? 'N/A'],
                    ['Order Status', $response['order_status'] ?? 'N/A'],
                    ['Auth Code', $response['auth_code'] ?? 'N/A'],
                    ['RRN', $response['ref_ret_num'] ?? 'N/A'],
                    ['Transaction ID', $response['transaction_id'] ?? 'N/A'],
                    ['Amount', ($response['first_amount'] ?? 0) . ' ' . ($response['currency'] ?? 'TRY')],
                    ['Masked Number', $response['masked_number'] ?? 'N/A'],
                    ['Transaction Time', isset($response['transaction_time']) ? $response['transaction_time']->format('Y-m-d H:i:s') : 'N/A'],
                ]
            );
            $this->line("");
        }

        // Hata bilgileri
        if (isset($response['error_code']) || isset($response['error_message'])) {
            $this->error("=== Hata Bilgileri ===");
            if (isset($response['error_code'])) {
                $this->error("Hata Kodu: " . $response['error_code']);
            }
            if (isset($response['error_message'])) {
                $this->error("Hata Mesajı: " . $response['error_message']);
            }
            $this->line("");
        }

        // Tam response (debug için)
        $this->info("=== Tam Response (JSON) ===");
        $this->line(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
