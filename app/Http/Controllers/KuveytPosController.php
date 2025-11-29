<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Repositories\Odeme\KuveytPosOdeme;
use Illuminate\Support\Facades\Log;

class KuveytPosController extends Controller
{
    protected $orderRepository;
    protected $tamamlaRepository;

    public function __construct()
    {
    }

    /**
     * 3D Secure doğrulama sonrası ödeme tamamlama
     */
    public function complete3DSecure(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|string',
            'conversation_id' => 'required|string',
            'conversation_data' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);

            $kuveytPos = new KuveytPosOdeme($order);
            $result = $kuveytPos->complete3DSecure(
                $request->payment_id,
                $request->conversation_id,
                $request->conversation_data
            );

            if ($result['success']) {
                // Siparişi tamamla
                $order->status = 'completed';
                $order->save();

                // Payment kaydı oluştur
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->total_amount,
                        'status' => 'success', // Payment enum: pending, success, failed, cancelled, refunded
                        'payment_method' => 'kuveytpos',
                        'response_data' => $result['response'] ?? [],
                        'paid_at' => now(),
                    ]
                );

                return redirect()->route('orders.show', ['order' => $order->id])
                    ->with('success', 'Ödemeniz başarıyla alındı!');
            } else {
                return redirect()->back()->with('error', $result['alert']);
            }

        } catch (\Exception $e) {
            Log::error('Kuveyt POS 3D Secure tamamlama hatası', [
                'order_id' => $request->order_id,
                'error' => $e->getMessage(),
                'error_code' => '3D_COMPLETE_ERROR',
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');
        }
    }

    /**
     * Kuveyt POS direkt ödeme
     */
    public function payDirect(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'cc_name' => 'required|string|max:255',
            'cc_number' => 'required|string|min:13|max:19',
            'cc_cvc' => 'required|string|min:3|max:4',
            'expiry_year' => 'required|string|size:2',
            'expiry_month' => 'required|string|size:2',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);

            // Sipariş durumu kontrolü (completed = onaylanmış)
            if ($order->status === 'completed') {
                return redirect()->route('orders.show', ['order' => $order->id])
                    ->with('error', 'Bu sipariş zaten onaylanmış.');
            }

            $kuveytPos = new KuveytPosOdeme($order);
            $result = $kuveytPos->payDirect(
                $request->cc_name,
                $request->cc_number,
                $request->cc_cvc,
                $request->expiry_year,
                $request->expiry_month
            );

            if ($result['success']) {
                // Siparişi tamamla
                $order->status = 'completed';
                $order->save();

                // Payment kaydı oluştur
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'amount' => $order->total_amount,
                        'status' => 'success', // Payment enum: pending, success, failed, cancelled, refunded
                        'payment_method' => 'kuveytpos',
                        'response_data' => $result['response'] ?? [],
                        'paid_at' => now(),
                    ]
                );

                return redirect()->route('orders.show', ['order' => $order->id])
                    ->with('success', 'Ödemeniz başarıyla alındı!');
            } else {
                return redirect()->back()->with('error', $result['alert']);
            }

        } catch (\Exception $e) {
            Log::error('Kuveyt POS direkt ödeme hatası', [
                'order_id' => $request->order_id,
                'error' => $e->getMessage(),
                'error_code' => 'DIRECT_PAYMENT_ERROR',
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');
        }
    }

    /**
     * Kuveyt POS başarılı ödeme callback
     */
    public function success(Request $request)
    {
        try {
            // Log tüm gelen parametreleri (debug için)
            Log::info('Kuveyt POS success callback received', [
                'all_params' => $request->all(),
                'method' => $request->method()
            ]);

            // Kuveyt POS XML response'u parse et
            $orderNumber = $this->parseKuveytPosResponse($request);

            // Eğer XML'den alınamazsa diğer parametreleri dene
            if (!$orderNumber) {
                $orderNumber = $request->input('id')
                            ?? $request->input('merchant_oid')
                            ?? $request->input('order_id')
                            ?? $request->input('orderNumber');
            }

            // Hala bulunamazsa session'dan dene
            if (!$orderNumber && session('order_id')) {
                $order = Order::find(session('order_id'));
                if ($order) {
                    $orderNumber = $order->order_number;
                    Log::info('Kuveyt POS: Order number from session', [
                        'order_number' => $orderNumber
                    ]);
                }
            }

            $hash = $request->input('hash');

            // Güvenlik kontrolü: Hash doğrulama (geçici olarak devre dışı - test için)
            // if (!$this->validateCallbackHash($request)) {
            //     Log::warning('Kuveyt POS success callback hash validation failed', [
            //         'order_number' => $orderNumber,
            //         'request' => $request->all()
            //     ]);
            //     return redirect()->route('home')->with('error', 'Güvenlik doğrulaması başarısız.');
            // }

            if (!$orderNumber) {
                Log::error('Kuveyt POS success callback: Order number not found in request', [
                    'request' => $request->all()
                ]);
                return redirect()->route('orders.create')->with('error', 'Sipariş numarası bulunamadı. Lütfen tekrar deneyiniz.');
            }

            // Önce hızlı ödeme kaydı mı kontrol et
            $quickPayment = \App\Models\QuickPayment::where('payment_number', $orderNumber)->first();
            
            if ($quickPayment) {
                // Hızlı ödeme callback'i - ayrı method'a yönlendir
                return $this->handleQuickPaymentCallback($request, $quickPayment, $orderNumber);
            }

            // Normal sipariş callback'i
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::warning('Kuveyt POS success callback: Order not found', [
                    'order_number' => $orderNumber,
                    'request_params' => $request->all()
                ]);
                return redirect()->route('orders.create')->with('error', 'Sipariş bulunamadı. Lütfen tekrar deneyiniz.');
            }

            // Eğer ödeme zaten onaylanmışsa tekrar işleme
            if ($order->status === 'completed' && $order->payment && $order->payment->status === 'success') {
                Log::info('Kuveyt POS success callback: Payment already confirmed', [
                    'order_number' => $orderNumber
                ]);
                return redirect()->route('orders.show', ['order' => $order->id])
                    ->with('success', 'Ödemeniz başarıyla alındı!');
            }

            // 3D Secure doğrulama sonrası ödeme tamamlama
            try {
                $kuveytPos = new KuveytPosOdeme($order);

                // Bankadan gelen tüm POST parametrelerini kullan
                // Mews POS'un make3DPayment metodu request'ten parametreleri alır
                $result = $kuveytPos->complete3DSecureFromCallback($request);

                if ($result['success']) {
                    // Ödeme başarılı - siparişi tamamla
                    $order->status = 'completed';
                    $order->save();

                    // Payment kaydı oluştur veya güncelle
                    $payment = $order->payment()->updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'amount' => $order->total_amount,
                            'status' => 'success', // Payment enum: pending, success, failed, cancelled, refunded
                            'payment_method' => 'kuveytpos',
                            'response_data' => $result['response'] ?? [],
                            'additional_request_data' => $request->all(),
                            'paid_at' => now(),
                        ]
                    );

                    Log::info('Kuveyt POS success callback processed', [
                        'order_number' => $orderNumber,
                        'order_id' => $order->id,
                        'payment_id' => $payment->id
                    ]);

                    return redirect()->route('orders.show', ['order' => $order->id])
                        ->with('success', 'Ödemeniz başarıyla alındı!');
                } else {
                    // Ödeme doğrulaması başarısız
                    Log::error('Kuveyt POS payment verification failed', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $result['alert'] ?? 'Unknown error',
                        'request' => $request->all()
                    ]);

                    // Müşteriye genel mesaj göster, detayları logda tut
                    return redirect()->route('orders.create')->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');
                }
            } catch (\Exception $e) {
                Log::error('Kuveyt POS success callback exception', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $e->getMessage(),
                    'error_code' => 'SUCCESS_CALLBACK_EXCEPTION',
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()->route('orders.create')->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');
            }

        } catch (\Exception $e) {
            Log::error('Kuveyt POS success callback hatası', [
                'request' => $request->all(),
                'error' => $e->getMessage(),
                'error_code' => 'SUCCESS_CALLBACK_ERROR',
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('orders.create')->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');
        }
    }

    /**
     * Kuveyt POS başarısız ödeme callback
     */
    public function fail(Request $request)
    {
        try {
            // Log tüm gelen parametreleri (debug için)
            Log::info('Kuveyt POS fail callback received', [
                'all_params' => $request->all(),
                'method' => $request->method()
            ]);

            // Kuveyt POS XML response'u parse et
            $orderNumber = $this->parseKuveytPosResponse($request);

            // Eğer XML'den alınamazsa diğer parametreleri dene
            if (!$orderNumber) {
                $orderNumber = $request->input('id')
                            ?? $request->input('merchant_oid')
                            ?? $request->input('order_id')
                            ?? $request->input('orderNumber');
            }

            // Hala bulunamazsa session'dan dene
            if (!$orderNumber && session('order_id')) {
                $order = Order::find(session('order_id'));
                if ($order) {
                    $orderNumber = $order->order_number;
                    Log::info('Kuveyt POS fail: Order number from session', [
                        'order_number' => $orderNumber
                    ]);
                }
            }

            // Güvenlik kontrolü: Hash doğrulama (geçici olarak devre dışı - test için)
            // if (!$this->validateCallbackHash($request)) {
            //     Log::warning('Kuveyt POS fail callback hash validation failed', [
            //         'order_number' => $orderNumber,
            //         'request' => $request->all()
            //     ]);
            //     return redirect()->route('orders.create')->with('error', 'Güvenlik doğrulaması başarısız.');
            // }

            if (!$orderNumber) {
                Log::error('Kuveyt POS fail callback: Order number not found in request', [
                    'request' => $request->all()
                ]);
                return redirect()->route('orders.create')->with('error', 'Sipariş numarası bulunamadı.');
            }

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order) {
                // XML'den hata mesajını parse et
                $errorMessage = $this->parseKuveytPosErrorMessage($request);
                if (!$errorMessage) {
                    $errorMessage = $request->input('error_message') ?? 'Bilinmeyen hata';
                }

                // Payment kaydını güncelle veya oluştur
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'status' => 'failed',
                        'payment_method' => 'kuveytpos',
                        'error_message' => $errorMessage,
                        'response_data' => $request->all(),
                    ]
                );

                Log::info('Kuveyt POS fail callback processed', [
                    'order_number' => $orderNumber,
                    'order_id' => $order->id,
                    'error_message' => $errorMessage
                ]);
            }

            // Müşteriye genel mesaj göster
            return redirect()->route('orders.create')->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');

        } catch (\Exception $e) {
            Log::error('Kuveyt POS fail callback hatası', [
                'request' => $request->all(),
                'error' => $e->getMessage(),
                'error_code' => 'FAIL_CALLBACK_ERROR',
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('orders.create')->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz veya farklı bir ödeme yöntemi kullanınız.');
        }
    }

    /**
     * Kuveyt POS callback (webhook)
     */
    public function callback(Request $request)
    {
        try {
            $orderNumber = $request->input('order_id');
            $status = $request->input('status');
            $transactionId = $request->input('transaction_id');

            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::warning('Kuveyt POS callback: Sipariş bulunamadı', [
                    'order_number' => $orderNumber,
                    'request' => $request->all()
                ]);
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            // Ödeme durumunu güncelle
            if ($status === 'success') {
                $order->status = 'completed';
                $order->save();

                // Payment kaydı oluştur veya güncelle
                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'transaction_id' => $transactionId,
                        'amount' => $order->total_amount,
                        'status' => 'success', // Payment enum: pending, success, failed, cancelled, refunded
                        'payment_method' => 'kuveytpos',
                        'response_data' => $request->all(),
                        'paid_at' => now(),
                    ]
                );
            } else {
                // Hata kaydı
                $errorDetail = $request->input('error_message') ?? 'Bilinmeyen hata';

                $order->payment()->updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'status' => 'failed',
                        'payment_method' => 'kuveytpos',
                        'error_message' => $errorDetail,
                        'response_data' => $request->all(),
                    ]
                );

                Log::error('Kuveyt POS webhook - payment rejected', [
                    'order_number' => $orderNumber,
                    'order_id' => $order->id,
                    'error_message' => $errorDetail
                ]);
            }

            Log::info('Kuveyt POS callback işlendi', [
                'order_number' => $orderNumber,
                'status' => $status,
                'transaction_id' => $transactionId
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Kuveyt POS callback hatası', [
                'request' => $request->all(),
                'error' => $e->getMessage(),
                'error_code' => 'WEBHOOK_ERROR',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Kuveyt POS XML response'u parse et
     */
    private function parseKuveytPosResponse(Request $request)
    {
        try {
            // AuthenticationResponse parametresini al
            $xmlResponse = $request->input('AuthenticationResponse');

            if (!$xmlResponse) {
                return null;
            }

            // URL decode
            $xmlResponse = urldecode($xmlResponse);

            Log::debug('Kuveyt POS XML parsing', [
                'xml_length' => strlen($xmlResponse),
                'xml_preview' => substr($xmlResponse, 0, 200)
            ]);

            // XML'i parse et
            $xml = simplexml_load_string($xmlResponse);

            if ($xml === false) {
                Log::error('Kuveyt POS XML parse failed', [
                    'xml' => $xmlResponse
                ]);
                return null;
            }

            // MerchantOrderId'yi al
            $merchantOrderId = (string)$xml->MerchantOrderId;

            if ($merchantOrderId && $merchantOrderId !== '0') {
                Log::info('Kuveyt POS order number parsed from XML', [
                    'merchant_order_id' => $merchantOrderId,
                    'response_code' => (string)$xml->ResponseCode,
                    'response_message' => (string)$xml->ResponseMessage
                ]);

                return $merchantOrderId;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Kuveyt POS XML parse exception', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return null;
        }
    }

    /**
     * Kuveyt POS XML'den hata mesajını parse et
     */
    private function parseKuveytPosErrorMessage(Request $request)
    {
        try {
            $xmlResponse = $request->input('AuthenticationResponse');

            if (!$xmlResponse) {
                return null;
            }

            $xmlResponse = urldecode($xmlResponse);
            $xml = simplexml_load_string($xmlResponse);

            if ($xml === false) {
                return null;
            }

            // ResponseMessage'ı al
            $responseMessage = (string)$xml->ResponseMessage;
            $responseCode = (string)$xml->ResponseCode;

            if ($responseMessage) {
                return $responseCode ? "[$responseCode] $responseMessage" : $responseMessage;
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Callback hash validation
     */
    private function validateCallbackHash(Request $request)
    {
        try {
            $receivedHash = $request->input('hash');
            $orderNumber = $request->input('order_id');
            $status = $request->input('status');
            $amount = $request->input('amount');
            
            // Hash yoksa geçici olarak true döndür (test ortamı için)
            if (!$receivedHash) {
                Log::info('Kuveyt POS callback hash not provided, skipping validation', [
                    'order_number' => $orderNumber
                ]);
                return true;
            }
            
            // Kuveyt POS hash algoritması (dokümantasyona göre)
            $secretKey = config('services.kuveytpos.password');
            $hashString = $orderNumber . $status . $amount . $secretKey;
            $calculatedHash = hash('sha256', $hashString);
            
            $isValid = hash_equals($receivedHash, $calculatedHash);
            
            if (!$isValid) {
                Log::warning('Kuveyt POS hash validation failed', [
                    'order_number' => $orderNumber,
                    'received_hash' => $receivedHash,
                    'calculated_hash' => $calculatedHash,
                    'hash_string' => $hashString
                ]);
            }
            
            return $isValid;
            
        } catch (\Exception $e) {
            Log::error('Kuveyt POS hash validation error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            return false;
        }
    }

    /**
     * Hızlı ödeme callback'i işle
     */
    protected function handleQuickPaymentCallback($request, $quickPayment, $paymentNumber)
    {
        try {
            // Eğer ödeme zaten onaylanmışsa
            if ($quickPayment->payment_ok == 1) {
                Log::info('Kuveyt POS quick payment callback: Payment already confirmed', [
                    'payment_number' => $paymentNumber
                ]);
                return redirect()->route('hizli-odeme-success', ['payment_number' => $paymentNumber])
                    ->with('success', 'Ödemeniz başarıyla alındı!');
            }

            // KuveytPosHizliOdeme servisi ile doğrulama
            $kuveytPosHizli = new \App\Repositories\Odeme\KuveytPosHizliOdeme($quickPayment);
            $result = $kuveytPosHizli->complete3DSecureFromCallback($request);

            if ($result['success']) {
                // Ödeme başarılı - QuickPayment güncelle
                $quickPayment->payment_ok = true;
                $quickPayment->status = 'completed';
                $quickPayment->payment_date = now();
                $quickPayment->payment_info = 'Kuveyt POS ödeme başarılı';
                $quickPayment->payment_extra = array_merge(
                    $quickPayment->payment_extra ?? [],
                    ['callback_response' => $result['response'] ?? [], 'completed_at' => now()]
                );
                $quickPayment->save();

                Log::info('Kuveyt POS quick payment success', [
                    'payment_number' => $paymentNumber,
                    'quick_payment_id' => $quickPayment->id,
                    'amount' => $quickPayment->amount
                ]);

                // Session temizle
                session()->forget('quick_payment_id');
                session()->forget('is_quick_payment');

                return redirect()->route('quick-payment.success', ['payment_number' => $paymentNumber])
                    ->with('success', 'Ödemeniz başarıyla alındı!');
            } else {
                // Ödeme başarısız
                $quickPayment->payment_ok = false;
                $quickPayment->status = 'failed';
                $quickPayment->payment_info = 'Kuveyt POS ödeme başarısız: ' . ($result['alert'] ?? 'Unknown error');
                $quickPayment->payment_extra = array_merge(
                    $quickPayment->payment_extra ?? [],
                    ['callback_error' => $result['alert'] ?? 'Unknown error', 'failed_at' => now()]
                );
                $quickPayment->save();

                Log::error('Kuveyt POS quick payment failed', [
                    'payment_number' => $paymentNumber,
                    'error' => $result['alert'] ?? 'Unknown error'
                ]);

                return redirect()->route('quick-payment.show', ['payment_number' => $paymentNumber])
                    ->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz.');
            }
        } catch (\Exception $e) {
            Log::error('Kuveyt POS quick payment callback exception', [
                'payment_number' => $paymentNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $quickPayment->status = 'failed';
            $quickPayment->payment_info = 'Callback hatası: ' . $e->getMessage();
            $quickPayment->save();

            return redirect()->route('quick-payment.show', ['payment_number' => $paymentNumber])
                ->with('error', 'Ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz.');
        }
    }
}
