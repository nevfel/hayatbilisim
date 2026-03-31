<?php

namespace App\Repositories\Odeme;

use App\Models\QuickPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaytrHizliOdeme
{
    public function __construct(private QuickPayment $quickPayment)
    {
    }

    /**
     * PayTR iFrame token (Hızlı Ödeme).
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

        $email = (string) ($this->quickPayment->gon_email ?: 'test@test.com');
        $name = (string) ($this->quickPayment->gon_adsoyad ?: 'Müşteri');
        $phone = (string) ($this->quickPayment->gon_phone ?: '');
        $phone = preg_replace('/[^0-9]/', '', $phone) ?: '0000000000';

        // PayTR get-token: user_address zorunlu. Hızlı ödeme modelinde adres yok, sabit değer veriyoruz.
        $address = (string) (($this->quickPayment->payment_extra['address'] ?? null) ?: 'Türkiye');

        $amount = (float) $this->quickPayment->amount;
        $paymentAmount = (int) round($amount * 100);

        $merchantOid = $this->createMerchantOid();

        $title = $this->quickPayment->description ?: ('Hızlı Ödeme ' . $this->quickPayment->payment_number);
        $basket = [
            [$title, (string) number_format($amount, 2, '.', ''), 1],
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

            // Dönüş sayfaları sabit de olabilir
            'merchant_ok_url' => (string) (config('paytr.success_url') ?: route('paytr.success-static')),
            'merchant_fail_url' => (string) (config('paytr.failure_url') ?: route('paytr.fail-static')),

            'debug_on' => (string) config('paytr.debug_on', 0),
            'timeout_limit' => '30',
            'lang' => 'tr',
        ];

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->post((string) config('paytr.token_url'), $payload);

            $data = $response->json();

            if (!$response->ok() || !is_array($data)) {
                Log::error('PayTR quick token request failed', [
                    'payment_number' => $this->quickPayment->payment_number,
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
                Log::error('PayTR quick token response error', [
                    'payment_number' => $this->quickPayment->payment_number,
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
            Log::error('PayTR quick token exception', [
                'payment_number' => $this->quickPayment->payment_number,
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
        // Alfanumerik şartı
        $base = (string) $this->quickPayment->payment_number;
        $base = preg_replace('/[^A-Za-z0-9]/', '', $base) ?: 'QP';
        $suffix = strtoupper(Str::random(10));
        $oid = strtoupper($base . $suffix);
        return Str::limit($oid, 64, '');
    }
}

