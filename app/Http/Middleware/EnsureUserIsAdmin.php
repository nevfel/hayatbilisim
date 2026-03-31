<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            return redirect()->route('welcome')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
        }

        return $next($request);
    }
}

