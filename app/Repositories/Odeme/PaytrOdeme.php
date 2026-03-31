<?php

namespace App\Repositories\Odeme;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaytrOdeme
{
    public function __construct(private Order $order)
    {
    }

    /**
     * PayTR iFrame token üretir.
     *
     * @return array{success:bool, token?:string, merchant_oid?:string, error?:string, raw?:array}
     */
    public function getToken(): array
    {
        $merchantId = (string) config('paytr.merchant_id');
        $merchantKey = (string) config('paytr.merchant_key');
        $merchantSalt = (string) config('paytr.merchant_salt');

        if ($merchantId === '' || $merchantKey === '' || $merchantSalt === '') {
            return [
                'success' => false,
                'error' => 'PayTR ayarları eksik (merchant_id / merchant_key / merchant_salt).',
            ];
        }

        $userIp = request()->ip() ?? '127.0.0.1';
        $email = (string) ($this->order->billing_email ?? 'test@test.com');
        $name = (string) ($this->order->billing_name ?? 'Müşteri');
        $phone = (string) ($this->order->billing_phone ?? '');
        $address = (string) ($this->order->billing_address ?? '');

        // PayTR kuruş ister
        $paymentAmount = (int) round(((float) $this->order->total_amount) * 100);

        // merchant_oid benzersiz olmalı
        $merchantOid = $this->createMerchantOid();

        // Sepet bilgisi (PayTR: base64(json))
        // Burada minimum çalışan format kullanıyoruz.
        $basket = [
            ['Sipariş ' . $this->order->order_number, (string) number_format((float) $this->order->total_amount, 2, '.', ''), 1],
        ];
        $userBasket = base64_encode(json_encode($basket, JSON_UNESCAPED_UNICODE));

        $noInstallment = (string) config('paytr.no_installment', 0);
        $maxInstallment = (string) config('paytr.max_installment', 0);
        $currency = (string) config('paytr.currency', 'TL');
        $testMode = (string) config('paytr.test_mode', 0);

        $hashStr = $merchantId . $userIp . $merchantOid . $email . $paymentAmount . $userBasket . $noInstallment . $maxInstallment . $currency . $testMode;
        $paytrToken = base64_encode(hash_hmac('sha256', $hashStr . $merchantSalt, $merchantKey, true));

        $payload = [
            'merchant_id' => $merchantId,
            'user_ip' => $userIp,
            'merchant_oid' => $merchantOid,
            'email' => $email,
            'payment_amount' => $paymentAmount,
            'paytr_token' => $paytrToken,
            'user_basket' => $userBasket,
            'no_installment' => $noInstallment,
            'max_installment' => $maxInstallment,
            'currency' => $currency,
            'test_mode' => $testMode,

            'user_name' => $name,
            'user_address' => $address,
            'user_phone' => $phone,

            // Kullanıcı bu sayfalara yönlenir; ödeme kesinleşmesi bildirim ile olur.
            'merchant_ok_url' => (string) (config('paytr.success_url') ?: route('paytr.success', ['order' => $this->order->id])),
            'merchant_fail_url' => (string) (config('paytr.failure_url') ?: route('paytr.fail', ['order' => $this->order->id])),

            'debug_on' => (string) config('paytr.debug_on', 0),
            'timeout_limit' => '30',
            'lang' => 'tr',

            // Bildirim URL'i PayTR panelinden tanımlanır, burada ayrıca gönderilmez.
        ];

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->post((string) config('paytr.token_url'), $payload);

            $data = $response->json();

            if (!$response->ok() || !is_array($data)) {
                Log::error('PayTR token request failed', [
                    'order_id' => $this->order->id,
                    'http_status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'error' => 'PayTR token alınamadı (HTTP hata).',
                    'raw' => ['status' => $response->status()],
                ];
            }

            if (($data['status'] ?? null) !== 'success' || empty($data['token'])) {
                Log::error('PayTR token response error', [
                    'order_id' => $this->order->id,
                    'response' => $data,
                ]);

                return [
                    'success' => false,
                    'error' => (string) ($data['reason'] ?? 'PayTR token alınamadı.'),
                    'raw' => $data,
                ];
            }

            return [
                'success' => true,
                'token' => (string) $data['token'],
                'merchant_oid' => $merchantOid,
                'raw' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('PayTR token exception', [
                'order_id' => $this->order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'PayTR token alınamadı (sistem hatası).',
            ];
        }
    }

    private function createMerchantOid(): string
    {
        // PayTR: merchant_oid alfanumerik olmalı, özel karakter içeremez.
        // PayTR 64 karakter sınırı var; kısa ve benzersiz tutalım.
        $base = (string) $this->order->order_number;
        $base = preg_replace('/[^A-Za-z0-9]/', '', $base) ?: ('ORDER' . $this->order->id);

        $suffix = strtoupper(Str::random(10));
        $oid = strtoupper($base . $suffix);

        return Str::limit($oid, 64, '');
    }
}

