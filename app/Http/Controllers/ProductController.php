<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function show()
    {
        $product = Product::where('is_active', true)->first();

        if (!$product) {
            abort(404);
        }

        return Inertia::render('Product/Show', [
            'product' => $product,
        ]);
    }
}
