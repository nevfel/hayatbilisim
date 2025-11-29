<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuickPayment;
use App\Repositories\Odeme\KuveytPosHizliOdeme;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class QuickPaymentController extends Controller
{
    /**
     * Hızlı ödeme linki oluştur (Admin için)
     */
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'gon_email' => 'required|email',
            'gon_adsoyad' => 'required|string|max:255',
            'gon_phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $quickPayment = QuickPayment::create([
                'amount' => $request->amount,
                'gon_email' => $request->gon_email,
                'gon_adsoyad' => $request->gon_adsoyad,
                'gon_phone' => $request->gon_phone,
                'description' => $request->description,
                'status' => 'pending',
                'payment_ok' => false,
            ]);

            Log::info('Hızlı ödeme linki oluşturuldu', [
                'payment_number' => $quickPayment->payment_number,
                'amount' => $quickPayment->amount,
                'email' => $quickPayment->gon_email,
            ]);

            return response()->json([
                'success' => true,
                'payment_number' => $quickPayment->payment_number,
                'payment_link' => $quickPayment->payment_link,
                'message' => 'Hızlı ödeme linki oluşturuldu.',
            ]);

        } catch (\Exception $e) {
            Log::error('Hızlı ödeme linki oluşturma hatası', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hızlı ödeme linki oluşturulamadı.',
            ], 500);
        }
    }

    /**
     * Hızlı ödeme sayfasını göster
     */
    public function show($payment_number)
    {
        $quickPayment = QuickPayment::where('payment_number', $payment_number)->firstOrFail();

        // Zaten ödenmiş mi kontrol et
        if ($quickPayment->isPaid()) {
            return redirect()->route('quick-payment.success', ['payment_number' => $payment_number])
                ->with('info', 'Bu ödeme zaten tamamlanmış.');
        }

        return Inertia::render('QuickPayment/Show', [
            'quickPayment' => $quickPayment,
        ]);
    }

    /**
     * Hızlı ödeme işlemini başlat (3D Secure)
     */
    public function pay(Request $request, $payment_number)
    {
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:13|max:19',
            'card_cvc' => 'required|string|min:3|max:4',
            'expiry_year' => 'required|string|size:2',
            'expiry_month' => 'required|string|size:2',
        ]);

        try {
            $quickPayment = QuickPayment::where('payment_number', $payment_number)->firstOrFail();

            // Zaten ödenmiş mi kontrol et
            if ($quickPayment->isPaid()) {
                return redirect()->route('quick-payment.success', ['payment_number' => $payment_number])
                    ->with('info', 'Bu ödeme zaten tamamlanmış.');
            }

            // Kuveyt POS Hızlı Ödeme 3D Secure başlat
            $kuveytPosHizli = new KuveytPosHizliOdeme($quickPayment);
            $result = $kuveytPosHizli->initialize3DSecure(
                $request->card_holder_name,
                $request->card_number,
                $request->card_cvc,
                $request->expiry_year,
                $request->expiry_month
            );

            if ($result['success']) {
                // Session'a quick payment bilgisini kaydet
                session()->put('quick_payment_id', $quickPayment->id);
                session()->put('is_quick_payment', true);

                // 3D Form data'yı Inertia ile frontend'e gönder
                return Inertia::render('Payment/ThreeDForm', [
                    'formData' => $result['form_data'],
                    'quickPayment' => $quickPayment,
                    'isQuickPayment' => true,
                ]);
            } else {
                return redirect()->back()
                    ->with('error', $result['alert'] ?? 'Ödeme işlemi başlatılamadı.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            Log::error('Hızlı ödeme başlatma hatası', [
                'payment_number' => $payment_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Ödeme işlemi başlatılamadı. Lütfen tekrar deneyiniz.')
                ->withInput();
        }
    }

    /**
     * Hızlı ödeme başarılı sayfası
     */
    public function success($payment_number)
    {
        $quickPayment = QuickPayment::where('payment_number', $payment_number)->firstOrFail();

        return Inertia::render('QuickPayment/Success', [
            'quickPayment' => $quickPayment,
        ]);
    }

    /**
     * Tüm hızlı ödemeleri listele (Admin için)
     */
    public function index(Request $request)
    {
        $query = QuickPayment::query();

        // Filtreleme
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('payment_number', 'like', '%' . $request->search . '%')
                  ->orWhere('gon_email', 'like', '%' . $request->search . '%')
                  ->orWhere('gon_adsoyad', 'like', '%' . $request->search . '%');
            });
        }

        $quickPayments = $query->orderBy('created_at', 'desc')->paginate(20);

        return Inertia::render('QuickPayment/Index', [
            'quickPayments' => $quickPayments,
            'filters' => $request->only(['status', 'search']),
        ]);
    }
}
