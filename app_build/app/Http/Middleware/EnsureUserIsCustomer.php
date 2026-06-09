<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;

class EnsureUserIsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user() instanceof Customer) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak. Khusus Customer.'
        ], 403);
    }
}
