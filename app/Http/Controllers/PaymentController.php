<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Repositories\Odeme\KuveytPosOdeme;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Ödeme sayfasını göster (Kredi Kartı Ödeme Formu)
     */
    public function initiate(Order $order)
    {
        // Sipariş kontrolü
        if ($order->status === 'completed') {
            return redirect()->route('orders.show', ['order' => $order->id])
                ->with('error', 'Bu sipariş zaten tamamlanmış.');
        }

        return Inertia::render('Payment/Initiate', [
            'order' => $order->load('items.product'),
        ]);
    }

    /**
     * 3D Secure ödeme işlemini başlat
     */
    public function start3DPayment(Request $request, Order $order)
    {
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:13|max:19',
            'card_cvc' => 'required|string|min:3|max:4',
            'expiry_year' => 'required|string|size:2',
            'expiry_month' => 'required|string|size:2',
        ]);

        try {
            // Sipariş kontrolü
            if ($order->status === 'completed') {
                return redirect()->route('orders.show', ['order' => $order->id])
                    ->with('error', 'Bu sipariş zaten tamamlanmış.');
            }

            // Kuveyt POS 3D Secure başlat
            $kuveytPos = new KuveytPosOdeme($order);
            $result = $kuveytPos->initialize3DSecure(
                $request->card_holder_name,
                $request->card_number,
                $request->card_cvc,
                $request->expiry_year,
                $request->expiry_month
            );

            if ($result['success']) {
                // 3D Form data'yı Inertia ile frontend'e gönder
                // Frontend bu form'u otomatik POST edecek
                return Inertia::render('Payment/ThreeDForm', [
                    'formData' => $result['form_data'],
                    'order' => $order,
                ]);
            } else {
                return redirect()->back()
                    ->with('error', $result['alert'] ?? 'Ödeme işlemi başlatılamadı.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            Log::error('Payment 3D başlatma hatası', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Ödeme işlemi başlatılamadı. Lütfen tekrar deneyiniz.')
                ->withInput();
        }
    }

    /**
     * Başarılı ödeme sayfası
     */
    public function success(Order $order)
    {
        // Ödeme kontrolü
        if (!$order->payment || $order->payment->status !== 'completed') {
            return redirect()->route('orders.show', ['order' => $order->id])
                ->with('error', 'Ödeme bilgisi bulunamadı.');
        }

        return Inertia::render('Payment/Success', [
            'order' => $order->load('items.product', 'payment'),
        ]);
    }

    /**
     * Başarısız ödeme sayfası
     */
    public function failed(Order $order)
    {
        return Inertia::render('Payment/Failed', [
            'order' => $order->load('items.product'),
            'payment' => $order->payment,
        ]);
    }
}
