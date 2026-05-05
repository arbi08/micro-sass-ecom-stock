<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //     $user = auth()->user();

        //     if (!$user || !$user->tenant) {
        //         abort(403, 'Tenant not found');
        //     }

        //     // 🔥 نخزنو tenant globally
        //     app()->instance('tenant', $user->tenant);

        //     return $next($request);
        //         if (!auth()->check()) {
        //     abort(403, 'Unauthorized');
        // }

        // $tenant = auth()->user()->tenant;

        // if (!$tenant) {
        //     // abort(403, 'Tenant not found');
        //     return redirect()->route('403');
        // }

        // // bind tenant globally
        // app()->instance('tenant', $tenant);

        // return $next($request);
        if (!auth()->check()) {
            return $next($request);
        }

        app()->instance('tenant_id', auth()->user()->tenant_id);

        return $next($request);
    }
}
