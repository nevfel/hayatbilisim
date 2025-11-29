<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    private function getCartQuery()
    {
        $query = Cart::with('product');
        
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', session()->getId());
        }
        
        return $query;
    }

    public function index()
    {
        // Sadece giriş yapmış kullanıcılar siparişlerini görebilir
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $orders = Order::with('items.product', 'payment')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function create()
    {
        $cartItems = $this->getCartQuery()->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $total = $cartItems->sum(function ($item) {
            $basePrice = $item->quantity * $item->product->price;
            $servicesPrice = 0;

            if ($item->selected_services) {
                foreach ($item->selected_services as $service) {
                    $servicesPrice += $service['price'] * $item->quantity;
                }
            }

            return $basePrice + $servicesPrice;
        });

        return Inertia::render('Orders/Create', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:10',
            'billing_country' => 'nullable|string|max:2',
            'shipping_name' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:10',
        ]);

        $cartItems = $this->getCartQuery()->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Sepetiniz boş.');
        }

        $total = $cartItems->sum(function ($item) {
            $basePrice = $item->quantity * $item->product->price;
            $servicesPrice = 0;

            if ($item->selected_services) {
                foreach ($item->selected_services as $service) {
                    $servicesPrice += $service['price'] * $item->quantity;
                }
            }

            return $basePrice + $servicesPrice;
        });

        // Sipariş oluştur
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => 'pending',
            'billing_name' => $request->billing_name,
            'billing_email' => $request->billing_email,
            'billing_phone' => $request->billing_phone,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'billing_postal_code' => $request->billing_postal_code,
            'billing_country' => $request->billing_country ?? 'TR',
            'shipping_name' => $request->shipping_name ?? $request->billing_name,
            'shipping_address' => $request->shipping_address ?? $request->billing_address,
            'shipping_city' => $request->shipping_city ?? $request->billing_city,
            'shipping_postal_code' => $request->shipping_postal_code ?? $request->billing_postal_code,
        ]);

        // Sipariş kalemlerini oluştur
        foreach ($cartItems as $cartItem) {
            $itemSubtotal = $cartItem->quantity * $cartItem->product->price;
            
            // Seçili hizmetlerin fiyatını ekle
            if ($cartItem->selected_services) {
                foreach ($cartItem->selected_services as $service) {
                    $itemSubtotal += $service['price'] * $cartItem->quantity;
                }
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'price' => $cartItem->product->price,
                'quantity' => $cartItem->quantity,
                'subtotal' => $itemSubtotal,
            ]);

            // Stok güncelle
            $cartItem->product->decrement('stock', $cartItem->quantity);
        }

        // NOT: Sepeti ödeme başarılı olduktan sonra temizleyeceğiz (PaymentController callback'te)
        // Bu sayede ödeme başarısız olursa kullanıcı sepetini kaybetmez

        // Payment kaydı oluştur
        \Log::info('OrderController::store - Payment kaydı oluşturuluyor', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total' => $total,
        ]);
        try {
            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
            ]);
            \Log::info('OrderController::store - Payment kaydı oluşturuldu');
        } catch (\Exception $e) {
            \Log::error('OrderController::store - Payment kaydı oluşturulamadı', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        // 3D ödeme sayfasına yönlendir (tam sayfa olarak açılması için)
        // Payment form Blade view olarak döndüğü için tam sayfa redirect yapılmalı
        // Inertia location redirect kullanarak tam sayfa yüklemesi sağla
        $paymentUrl = route('payment.initiate', $order);
        \Log::info('OrderController::store - Payment sayfasına yönlendiriliyor', [
            'payment_url' => $paymentUrl,
            'is_inertia' => request()->header('X-Inertia'),
            'redirect_method' => 'location',
        ]);

        // Inertia location redirect - tam sayfa yüklemesi yapar
        return \Inertia\Inertia::location($paymentUrl);
    }

    public function show(Order $order)
    {
        // Yetki kontrolü
        if (auth()->check()) {
            if ($order->user_id !== auth()->id()) {
                abort(403);
            }
        } else {
            // Misafir kullanıcılar için session kontrolü yapılabilir
            // Şimdilik sadece giriş yapmış kullanıcılar görebilir
            return redirect()->route('login');
        }

        $order->load('items.product', 'payment');

        return Inertia::render('Orders/Show', [
            'order' => $order,
        ]);
    }
}
