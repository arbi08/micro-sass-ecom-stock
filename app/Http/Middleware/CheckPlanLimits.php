<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;

class CheckPlanLimits
{
    public function handle($request, Closure $next)
    {
        $tenant = auth()->user()->tenant;

        $limit = $tenant->subscription->plan->features['max_products'] ?? null;

        if ($limit) {

            // ⚡ cached count
            $count = Cache::remember(
                "tenant_{$tenant->id}_products_count",
                60,
                function () use ($tenant) {
                    return Product::where('tenant_id', $tenant->id)->count();
                }
            );

            if ($count >= $limit) {
                abort(403, 'Plan limit reached');
            }
        }

        return $next($request);
    }
}