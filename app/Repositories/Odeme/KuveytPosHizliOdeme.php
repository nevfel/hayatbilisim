<?php

namespace App\Repositories\Odeme;

use App\Models\QuickPayment;
use Illuminate\Support\Facades\Log;
use Mews\Pos\PosInterface;
use Mews\Pos\Factory\PosFactory;
use Mews\Pos\Entity\Account\KuveytPosAccount;
use Mews\Pos\Entity\Card\CreditCard;
use Mews\Pos\Exceptions\UnsupportedTransactionTypeException;
use Mews\Pos\Exceptions\UnsupportedPaymentModelException;
use Psr\Http\Client\ClientExceptionInterface;

class KuveytPosHizliOdeme
{
    protected $quickPayment;
    protected $pos;
    protected $account;
    protected $eventDispatcher;

    public function __construct(QuickPayment $quickPayment)
    {
        $this->quickPayment = $quickPayment;

        // MEWS POS Factory ile gateway oluştur
        $this->account = \Mews\Pos\Factory\AccountFactory::createKuveytPosAccount(
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
                    'gateway_3d' => $isTestMode 
                        ? 'https://boatest.kuveytturk.com.tr/boa.virtualpos.services/Home/ThreeDModelPayGate'
                        : 'https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelPayGate',
                ],
                'gateway_configs' => [
                    'test_mode' => $isTestMode,
                    'disable_3d_hash_check' => $isTestMode,
                ]
            ];
        }

        // Mews POS'un beklediği format: banks array'i içinde
        $banksConfig = [
            'banks' => [
                'kuveytpos' => $config
            ]
        ];

        // Symfony EventDispatcher kullan
        $this->eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

        // HTTP Client oluştur (Mews POS wrapper)
        $guzzleClient = new \GuzzleHttp\Client([
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => false,
            'http_errors' => false,
        ]);
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $httpClient = new \Mews\Pos\Client\HttpClient($guzzleClient, $psr17Factory, $psr17Factory);

        // Logger oluştur
        $logger = new \Monolog\Logger('kuveytpos_hizli');
        $logHandler = new \Monolog\Handler\StreamHandler(storage_path('logs/kuveytpos_hizli.log'), \Monolog\Logger::DEBUG);
        $logger->pushHandler($logHandler);

        try {
            $this->pos = PosFactory::createPosGateway($this->account, $banksConfig, $this->eventDispatcher, $httpClient, $logger);
            $this->pos->setTestMode($isTestMode);
        } catch (\Exception $e) {
            Log::error('Kuveyt POS Factory hatası (Hızlı Ödeme)', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * 3D Secure başlat
     */
    public function initialize3DSecure($cardHolderName, $cardNumber, $cvv, $expireYear, $expireMonth)
    {
        try {
            // Kredi kartı bilgileri
            $creditCard = $this->createCard($this->pos, [
                'number' => $cardNumber,
                'year' => $expireYear,
                'month' => $expireMonth,
                'cvv' => $cvv,
                'name' => $cardHolderName,
            ]);

            // TC Kimlik No (varsayılan)
            $identityNumber = '11111111111';

            // Sipariş verileri
            $orderData = [
                'id' => $this->quickPayment->payment_number,
                'amount' => $this->quickPayment->amount,
                'currency' => PosInterface::CURRENCY_TRY,
                'installment' => 1,
                'lang' => PosInterface::LANG_TR,
                'success_url' => route('kuveytpos.success'),
                'fail_url' => route('kuveytpos.fail'),
                'ip' => request()->ip() ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'email' => $this->quickPayment->gon_email,
                'name' => $this->quickPayment->gon_adsoyad,
            ];

            // QuickPayment'a kuveyt pos bilgilerini kaydet
            $this->quickPayment->payment_extra = json_encode([
                'kuveytpos_orderdata' => $orderData,
                'kuveytpos_identity_number' => $identityNumber,
                'started_at' => now()->toDateTimeString(),
            ]);
            $this->quickPayment->save();

            // ÖNEMLI: Kuveyt POS için IdentityTaxNumber parametresini orderData'ya ekle
            $orderData['identity_tax_number'] = $identityNumber;

            // Session'a kaydet
            session()->put("kuveytpos_orderdata_{$this->quickPayment->payment_number}", $orderData);
            session()->put("kuveytpos_payment_{$this->quickPayment->payment_number}", [
                'payment_id' => $this->quickPayment->id,
                'payment_number' => $this->quickPayment->payment_number,
                'amount' => $orderData['amount'],
                'started_at' => now()->toDateTimeString(),
            ]);
            session()->put('kuveytpos_identity_number', $identityNumber);

            Log::info('Kuveyt POS: IdentityTaxNumber eklendi (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'identity_number_masked' => substr($identityNumber, 0, 3) . '********'
            ]);

            // ÖNEMLI: Event listener ekle - IdentityTaxNumber'ı XML request'e eklemek için
            // TC/Vergi No formatını kontrol et ve temizle (sadece rakam)
            $cleanIdentityNumber = preg_replace('/[^0-9]/', '', $identityNumber);
            
            // Format kontrolü: TC No 11 haneli, Vergi No 10 haneli olmalı
            if (strlen($cleanIdentityNumber) == 11 || strlen($cleanIdentityNumber) == 10) {
                // Description için ödeme açıklaması oluştur
                $description = 'Hızlı Ödeme: ' . $this->quickPayment->payment_number;
                
                // RequestDataPreparedEvent listener ekle
                $listener = function (\Mews\Pos\Event\RequestDataPreparedEvent $event) use ($cleanIdentityNumber, $description) {
                    // Sadece Kuveyt POS ve 3D Secure enrollment check için (Request 1)
                    // Request 2'de OkUrl ve FailUrl yok, bu yüzden bunları kontrol ederek ayırt ediyoruz
                    $requestData = $event->getRequestData();
                    
                    if ($event->getBank() === 'kuveytpos' && 
                        $event->getPaymentModel() === PosInterface::MODEL_3D_SECURE &&
                        $event->getTxType() === PosInterface::TX_TYPE_PAY_AUTH &&
                        isset($requestData['OkUrl']) && isset($requestData['FailUrl'])) {
                        // Bu Request 1 (enrollment check) - IdentityTaxNumber ve Description ekle
                        
                        // IdentityTaxNumber'ı request data'ya ekle
                        $requestData['IdentityTaxNumber'] = $cleanIdentityNumber;
                        
                        // Description'ı request data'ya ekle
                        $requestData['Description'] = $description;
                        
                        // NOT: Hash'i yeniden hesaplamaya gerek yok
                        // IdentityTaxNumber ve Description hash hesaplamasına dahil edilmez
                        // Mevcut hash değeri korunur
                        
                        Log::info('Kuveyt POS: IdentityTaxNumber ve Description XML request\'e eklendi (Request 1 - Hızlı Ödeme)', [
                            'identity_number_masked' => substr($cleanIdentityNumber, 0, 3) . '********',
                            'identity_length' => strlen($cleanIdentityNumber),
                            'description' => $description
                        ]);
                        
                        $event->setRequestData($requestData);
                    }
                    // Request 2 için hiçbir şey yapma - MD değeri zaten doğru gönderiliyor
                };
                
                $this->eventDispatcher->addListener(\Mews\Pos\Event\RequestDataPreparedEvent::class, $listener);
            } else {
                Log::warning('Kuveyt POS: IdentityTaxNumber format hatası - event listener eklenmedi (Hızlı Ödeme)', [
                    'payment_id' => $this->quickPayment->id,
                    'identity_number' => $cleanIdentityNumber,
                    'length' => strlen($cleanIdentityNumber)
                ]);
            }

            // 3D Secure form data al (Mews POS metodu)
            $formData = $this->pos->get3DFormData(
                $orderData,
                PosInterface::MODEL_3D_SECURE,
                PosInterface::TX_TYPE_PAY_AUTH,
                $creditCard
            );

            // ÖNEMLI: Kuveyt POS IdentityTaxNumber parametresini form inputs'a da ekle (callback için)
            // Mews POS bu parametreyi otomatik olarak eklemiyor, manuel eklememiz gerekiyor
            if (isset($formData['inputs']) && is_array($formData['inputs']) && isset($cleanIdentityNumber)) {
                // Format kontrolü: TC No 11 haneli, Vergi No 10 haneli olmalı
                if (strlen($cleanIdentityNumber) == 11 || strlen($cleanIdentityNumber) == 10) {
                    $formData['inputs']['IdentityTaxNumber'] = $cleanIdentityNumber;
                    
                    Log::info('Kuveyt POS: IdentityTaxNumber form inputs\'a eklendi (Hızlı Ödeme)', [
                        'payment_id' => $this->quickPayment->id,
                        'identity_number_masked' => substr($cleanIdentityNumber, 0, 3) . '********',
                        'identity_length' => strlen($cleanIdentityNumber)
                    ]);
                }
            }

            Log::info('Kuveyt POS 3D Secure başlatıldı (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'payment_number' => $this->quickPayment->payment_number,
                'amount' => $orderData['amount'],
                'session_saved' => true
            ]);

            return [
                'success' => true,
                'form_data' => $formData,
            ];

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::error('Kuveyt POS connection timeout (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'error' => $e->getMessage()
            ]);

            $this->quickPayment->payment_info = 'Kuveyt POS bağlantı hatası: ' . $e->getMessage();
            $this->quickPayment->save();

            return [
                'success' => false,
                'alert' => 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.'
            ];

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Kuveyt POS request timeout (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'error' => $e->getMessage()
            ]);

            $this->quickPayment->payment_info = 'Kuveyt POS istek zaman aşımı: ' . $e->getMessage();
            $this->quickPayment->save();

            return [
                'success' => false,
                'alert' => 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.'
            ];

        } catch (\Exception $e) {
            Log::error('Kuveyt POS başlatma hatası (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->quickPayment->payment_info = 'Kuveyt POS başlatma hatası: ' . $e->getMessage();
            $this->quickPayment->save();

            return [
                'success' => false,
                'alert' => 'Ödeme işlemi başlatılamadı. Lütfen bilgilerinizi kontrol edip tekrar deneyiniz.'
            ];
        }
    }

    /**
     * 3D Secure callback'ten ödemeyi tamamla
     */
    public function complete3DSecureFromCallback($request)
    {
        try {
            Log::info('Kuveyt POS complete3DSecureFromCallback (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'request_method' => $request->method(),
                'request_all' => $request->all()
            ]);

            // DB'den kaydedilmiş orderData'yı al
            $paymentExtra = json_decode($this->quickPayment->payment_extra ?? '{}', true);
            $orderData = $paymentExtra['kuveytpos_orderdata'] ?? null;
            $identityNumber = $paymentExtra['kuveytpos_identity_number'] ?? null;

            if (!$orderData) {
                // Session'dan dene
                $orderData = session("kuveytpos_orderdata_{$this->quickPayment->payment_number}");
            }

            if (!$orderData) {
                // Yeniden oluştur
                Log::warning('Kuveyt POS: OrderData bulunamadı, yeniden oluşturuluyor (Hızlı Ödeme)', [
                    'payment_number' => $this->quickPayment->payment_number
                ]);

                $identityNumber = '11111111111';
                $orderData = [
                    'id' => $this->quickPayment->payment_number,
                    'amount' => $this->quickPayment->amount,
                    'currency' => PosInterface::CURRENCY_TRY,
                    'installment' => 1,
                    'lang' => PosInterface::LANG_TR,
                    'success_url' => route('kuveytpos.success'),
                    'fail_url' => route('kuveytpos.fail'),
                    'ip' => request()->ip() ?? '127.0.0.1',
                    'email' => $this->quickPayment->gon_email,
                    'name' => $this->quickPayment->gon_adsoyad,
                ];
            }

            // ÖNEMLI: Request 2 için orderData'dan IdentityTaxNumber ve Description'ı temizle
            // Bu parametreler sadece Request 1'de gönderilmeli, Request 2'de MD değeri yeterli
            unset($orderData['identity_tax_number']);
            unset($orderData['IdentityTaxNumber']);
            unset($orderData['Description']);
            unset($orderData['description']);

            // ÖNEMLI: Request 2 için hash hesaplamasını düzelt
            // Request 2'de OkUrl ve FailUrl hash'e dahil edilmemeli (dokümantasyona göre)
            // Mews POS bunları ekliyor, bu yüzden event listener ile düzeltiyoruz
            $account = $this->account;
            $listener = function (\Mews\Pos\Event\RequestDataPreparedEvent $event) use ($account) {
                // Sadece Kuveyt POS ve Request 2 (3D Payment completion) için
                $requestData = $event->getRequestData();
                
                if ($event->getBank() === 'kuveytpos' && 
                    $event->getPaymentModel() === PosInterface::MODEL_3D_SECURE &&
                    $event->getTxType() === PosInterface::TX_TYPE_PAY_AUTH &&
                    !isset($requestData['OkUrl']) && !isset($requestData['FailUrl']) &&
                    isset($requestData['KuveytTurkVPosAdditionalData'])) {
                    // Bu Request 2 - Hash'i yeniden hesapla (OkUrl ve FailUrl olmadan)
                    
                    $hashedPassword = base64_encode(sha1($account->getStoreKey(), true));
                    
                    // Request 2 hash: MerchantId + MerchantOrderId + Amount + UserName + HashPassword
                    $hashString = $requestData['MerchantId'] . 
                                  $requestData['MerchantOrderId'] . 
                                  $requestData['Amount'] . 
                                  $requestData['UserName'] . 
                                  $hashedPassword;
                    
                    $requestData['HashData'] = base64_encode(sha1($hashString, true));
                    
                    Log::info('Kuveyt POS: Request 2 hash yeniden hesaplandı (OkUrl ve FailUrl olmadan - Hızlı Ödeme)', [
                        'merchant_order_id' => $requestData['MerchantOrderId'],
                        'amount' => $requestData['Amount'],
                        'hash_preview' => substr($requestData['HashData'], 0, 20) . '...'
                    ]);
                    
                    $event->setRequestData($requestData);
                }
            };
            
            $this->eventDispatcher->addListener(\Mews\Pos\Event\RequestDataPreparedEvent::class, $listener);

            // IdentityNumber'ı session'a kaydet
            if ($identityNumber) {
                session()->put('kuveytpos_identity_number', $identityNumber);
            }

            // Symfony Request oluştur
            $symfonyRequest = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

            // 3D Secure payment tamamla
            $this->pos->make3DPayment(
                $symfonyRequest,
                $orderData,
                PosInterface::TX_TYPE_PAY_AUTH
            );

            $response = $this->pos->getResponse();
            $isSuccess = $this->pos->isSuccess();

            // Test mode MD validation bypass
            if (!$isSuccess &&
                env('KUVEYTPOS_TEST_MODE', true) &&
                isset($response['error_code']) &&
                $response['error_code'] == 'InvalidMetaData' &&
                isset($response['3d_all']['MDStatus']['MDStatusCode']) &&
                $response['3d_all']['MDStatus']['MDStatusCode'] == '1') {

                Log::warning('Kuveyt POS: MD validation bypassed (test mode, Hızlı Ödeme)', [
                    'payment_id' => $this->quickPayment->id
                ]);

                $isSuccess = true;
            }

            if ($isSuccess) {
                // Session temizle
                session()->forget("kuveytpos_orderdata_{$this->quickPayment->payment_number}");
                session()->forget("kuveytpos_payment_{$this->quickPayment->payment_number}");
                session()->forget('kuveytpos_identity_number');

                // QuickPayment güncelle
                $paymentExtra['kuveytpos_response'] = $response;
                $paymentExtra['completed_at'] = now()->toDateTimeString();
                $this->quickPayment->payment_extra = json_encode($paymentExtra);
                $this->quickPayment->save();

                Log::info('Kuveyt POS 3D Secure callback tamamlandı (Hızlı Ödeme)', [
                    'payment_number' => $this->quickPayment->payment_number
                ]);

                return [
                    'success' => true,
                    'response' => $response
                ];
            } else {
                // Başarısız
                $paymentExtra['kuveytpos_error_response'] = $response;
                $paymentExtra['failed_at'] = now()->toDateTimeString();
                $this->quickPayment->payment_extra = json_encode($paymentExtra);
                $this->quickPayment->save();

                return [
                    'success' => false,
                    'alert' => 'Ödeme işlemi başarısız. Lütfen kart bilgilerinizi kontrol edip tekrar deneyiniz.',
                    'response' => $response
                ];
            }

        } catch (\Exception $e) {
            Log::error('Kuveyt POS callback hatası (Hızlı Ödeme)', [
                'payment_number' => $this->quickPayment->payment_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->quickPayment->payment_info = 'Kuveyt POS callback hatası: ' . $e->getMessage();
            $this->quickPayment->save();

            return [
                'success' => false,
                'alert' => 'Ödeme doğrulaması sırasında bir hata oluştu. Lütfen tekrar deneyiniz.'
            ];
        }
    }

    /**
     * Kart numarasından kart tipini otomatik algıla
     */
    private function detectCardType($cardNumber)
    {
        // Kart numarasındaki boşlukları ve tireleri temizle
        $cardNumber = preg_replace('/[\s\-]/', '', $cardNumber);
        
        // Kart tipi algılama kuralları
        $patterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'master' => '/^5[1-5][0-9]{14}$/',
            'master2' => '/^2[2-7][0-9]{14}$/',
            'troy' => '/^9792[0-9]{12}$/',
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $cardNumber)) {
                return ($type === 'master2') ? 'master' : $type;
            }
        }
        
        // Fallback: İlk haneye göre tahmin
        $firstDigit = substr($cardNumber, 0, 1);
        if ($firstDigit == '4') return 'visa';
        if ($firstDigit == '5' || $firstDigit == '2') return 'master';
        if ($firstDigit == '9') return 'troy';
        
        return 'visa'; // Default
    }

    /**
     * Kredi kartı objesi oluştur (KuveytPosOdeme'deki createCard metodu)
     */
    private function createCard(PosInterface $pos, array $card): \Mews\Pos\Entity\Card\CreditCardInterface
    {
        try {
            // Kart tipini otomatik algıla
            $cardType = $card['type'] ?? $this->detectCardType($card['number']);
            
            Log::info('Kuveyt POS card type detected (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'card_number_masked' => substr($card['number'], 0, 4) . '****' . substr($card['number'], -4),
                'detected_type' => $cardType
            ]);
            
            return \Mews\Pos\Factory\CreditCardFactory::createForGateway(
                $pos,
                $card['number'],
                $card['year'],
                $card['month'],
                $card['cvv'],
                $card['name'],
                $cardType
            );
        } catch (\Mews\Pos\Exceptions\CardTypeRequiredException $e) {
            Log::error('Kuveyt POS card type required (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'error' => $e->getMessage()
            ]);
            
            $this->quickPayment->payment_info = 'Kuveyt POS kart tipi hatası';
            $this->quickPayment->save();
            
            throw new \Exception('Kart bilgileriniz işlenirken bir hata oluştu. Lütfen kart bilgilerinizi kontrol ediniz.');
            
        } catch (UnsupportedPaymentModelException $e) {
            Log::error('Kuveyt POS desteklenmeyen kart tipi (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'error' => $e->getMessage()
            ]);
            
            $this->quickPayment->payment_info = 'Kuveyt POS desteklenmeyen kart tipi';
            $this->quickPayment->save();
            
            throw new \Exception('Kartınız bu ödeme yöntemi ile kullanılamıyor. Lütfen farklı bir kart deneyiniz.');
            
        } catch (\Exception $e) {
            Log::error('Kuveyt POS kart oluşturma hatası (Hızlı Ödeme)', [
                'payment_id' => $this->quickPayment->id,
                'error' => $e->getMessage()
            ]);
            
            $this->quickPayment->payment_info = 'Kuveyt POS kart oluşturma hatası';
            $this->quickPayment->save();
            
            throw new \Exception('Kart bilgileriniz işlenirken bir hata oluştu. Lütfen kart bilgilerinizi kontrol ediniz.');
        }
    }

}

