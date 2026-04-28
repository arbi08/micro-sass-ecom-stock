<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function create(array $data)
    {
        $tenant = auth()->user()->tenant;

        $product = Product::create([
            ...$data,
            'tenant_id' => $tenant->id,
        ]);

        // 🔥 clear cache after change
        Cache::forget("tenant_{$tenant->id}_products_count");

        return $product;
    }
}