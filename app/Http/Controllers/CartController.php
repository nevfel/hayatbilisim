<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
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
        $cartItems = $this->getCartQuery()->get();

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

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'selected_services' => 'nullable|array',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Stok kontrolü
        if ($product->stock < $request->quantity) {
            return back()->withErrors(['stock' => 'Yeterli stok bulunmamaktadır.']);
        }

        $cartData = [
            'product_id' => $request->product_id,
        ];

        if (auth()->check()) {
            $cartData['user_id'] = auth()->id();
            $cartData['session_id'] = null;
        } else {
            $cartData['user_id'] = null;
            $cartData['session_id'] = session()->getId();
        }

        $cartItem = Cart::updateOrCreate(
            $cartData,
            [
                'quantity' => $request->quantity,
                'selected_services' => $request->selected_services,
            ]
        );

        return back()->with('success', 'Ürün sepete eklendi.');
    }

    public function update(Request $request, Cart $cart)
    {
        // Yetki kontrolü
        if (auth()->check()) {
            if ($cart->user_id !== auth()->id()) {
                abort(403);
            }
        } else {
            if ($cart->session_id !== session()->getId()) {
                abort(403);
            }
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Stok kontrolü
        if ($cart->product->stock < $request->quantity) {
            return back()->withErrors(['stock' => 'Yeterli stok bulunmamaktadır.']);
        }

        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Sepet güncellendi.');
    }

    public function destroy(Cart $cart)
    {
        // Yetki kontrolü
        if (auth()->check()) {
            if ($cart->user_id !== auth()->id()) {
                abort(403);
            }
        } else {
            if ($cart->session_id !== session()->getId()) {
                abort(403);
            }
        }

        $cart->delete();

        return back()->with('success', 'Ürün sepetten çıkarıldı.');
    }
}
