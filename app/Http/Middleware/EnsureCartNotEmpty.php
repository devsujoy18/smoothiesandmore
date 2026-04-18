<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCartNotEmpty
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app(CartService::class)->count() < 1) {
            return redirect()
                ->route('home')
                ->with('error', 'Your bag is empty. Add items before checkout.');
        }

        return $next($request);
    }
}
