<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\Odeme\PaytrOdeme;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class PaytrController extends Controller
{
    /**
     * PayTR iFrame ödeme başlat (token al ve iframe göster)
     */
    public function token(Order $order)
    {
        if ($order->status === 'completed') {
            return redirect()->route('orders.show', ['order' => $order->id])
                ->with('error', 'Bu sipariş zaten tamamlanmış.');
        }

        $paytrOdeme = new PaytrOdeme($order);
        $result = $paytrOdeme->getToken();

        if (!$result['success']) {
            return redirect()->route('payment.initiate', ['order' => $order->id])
                ->with('error', $result['error'] ?? 'PayTR ödeme başlatılamadı.');
        }

        // Payment kaydı: pending
        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'transaction_id' => $result['merchant_oid'] ?? null,
                'amount' => $order->total_amount,
                'status' => 'pending',
                'payment_method' => 'paytr',
                'initial_request_data' => [
                    'merchant_oid' => $result['merchant_oid'] ?? null,
                ],
            ]
        );

        // Order'da da takip amaçlı sakla
        $order->payment_extra = array_merge($order->payment_extra ?? [], [
            'paytr' => [
                'merchant_oid' => $result['merchant_oid'] ?? null,
                'started_at' => now()->toDateTimeString(),
            ],
        ]);
        $order->save();

        return view('paytr.pay', ['token' => $result['token']]);
    }

    /**
     * PayTR bildirim (webhook) - ödeme burada kesinleşir
     */
    public function bildirim(Request $request)
    {
        $merchantKey = (string) config('paytr.merchant_key');
        $merchantSalt = (string) config('paytr.merchant_salt');

        $merchantOid = (string) $request->input('merchant_oid');
        $status = (string) $request->input('status');
        $totalAmount = (string) $request->input('total_amount'); // kuruş
        $receivedHash = (string) $request->input('hash');

        $calculatedHash = base64_encode(hash_hmac(
            'sha256',
            $merchantOid . $merchantSalt . $status . $totalAmount,
            $merchantKey,
            true
        ));

        if (!hash_equals($calculatedHash, $receivedHash)) {
            Log::error('PAYTR notification failed: invalid hash', [
                'merchant_oid' => $merchantOid,
            ]);
            return 'OK';
        }

        // Order'ı mümkün olan en güvenli şekilde bul
        $order = Order::whereHas('payment', function ($q) use ($merchantOid) {
            $q->where('transaction_id', $merchantOid);
        })->first();

        if (!$order) {
            Log::error('PAYTR notification failed: order not found', [
                'merchant_oid' => $merchantOid,
            ]);
            return 'OK';
        }

        // Aynı sipariş için ikinci kez success gelirse idempotent davran
        if ($order->status === 'completed' && $order->payment && $order->payment->status === 'success') {
            return 'OK';
        }

        if ($status === 'success') {
            $paidAmount = ((int) $totalAmount) / 100;

            $order->status = 'completed';
            $order->payment_info = 'PayTR ödeme alındı' . ($request->filled('installment_count') ? ('. ' . $request->input('installment_count') . ' taksit') : '');
            $order->payment_extra = array_merge($order->payment_extra ?? [], [
                'paytr' => array_merge(($order->payment_extra['paytr'] ?? []), [
                    'notification' => $request->all(),
                    'paid_amount' => $paidAmount,
                    'confirmed_at' => now()->toDateTimeString(),
                ]),
            ]);
            $order->save();

            $order->payment()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id' => $merchantOid,
                    'amount' => $paidAmount,
                    'status' => 'success',
                    'payment_method' => 'paytr',
                    'response_data' => $request->all(),
                    'paid_at' => now(),
                ]
            );
        } else {
            $failMsg = trim('PayTR ödeme başarısız ' . ($request->input('failed_reason_code') ?? '') . ' ' . ($request->input('failed_reason_msg') ?? ''));

            $order->payment_info = $failMsg;
            $order->payment_extra = array_merge($order->payment_extra ?? [], [
                'paytr' => array_merge(($order->payment_extra['paytr'] ?? []), [
                    'notification' => $request->all(),
                    'failed_at' => now()->toDateTimeString(),
                ]),
            ]);
            $order->save();

            $order->payment()->updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id' => $merchantOid,
                    'amount' => $order->total_amount,
                    'status' => 'failed',
                    'payment_method' => 'paytr',
                    'response_data' => $request->all(),
                    'error_message' => $failMsg,
                ]
            );
        }

        return 'OK';
    }

    public function success(Order $order)
    {
        Log::info('paytr success return', [
            'order_id' => $order->id,
            'query' => request()->all(),
        ]);

        // PayTR'da kesinleşme bildirim ile olduğundan kullanıcıya sipariş ekranını göster
        if ($order->status === 'completed') {
            return redirect()->route('orders.show', ['order' => $order->id])
                ->with('success', 'Ödemeniz başarıyla alındı!');
        }

        return redirect()->route('orders.show', ['order' => $order->id])
            ->with('success', 'Ödeme isteğiniz alındı. Kesinleşmesi için PayTR bildirimi bekleniyor.');
    }

    public function fail(Order $order)
    {
        Log::info('paytr fail return', [
            'order_id' => $order->id,
            'query' => request()->all(),
        ]);

        return redirect()->route('payment.initiate', ['order' => $order->id])
            ->with('error', 'Ödeme başarısız veya iptal edildi. Lütfen tekrar deneyiniz.');
    }
}
