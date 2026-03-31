<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QuickPaymentApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $expected = (string) config('site.quick_payment_api_key', '');

        // Eğer key tanımlı değilse, public endpoint'i kapalı tutalım
        if ($expected === '') {
            return response()->json([
                'success' => false,
                'message' => 'Quick payment API kapalı.',
            ], 403);
        }

        $provided = (string) ($request->header('X-API-KEY') ?: $request->input('api_key'));

        if (!hash_equals($expected, $provided)) {
            return response()->json([
                'success' => false,
                'message' => 'Yetkisiz.',
            ], 401);
        }

        return $next($request);
    }
}

