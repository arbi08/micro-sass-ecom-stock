<?php

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function receive(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {

            foreach ($purchase->items as $item) {

                // 🔥 increase stock
                app(StockService::class)->increase(
                    $item->product_id,
                    $purchase->warehouse_id,
                    $item->quantity
                );
            }

            $purchase->update([
                'status' => 'received',
                'received_at' => now()
            ]);
        });
    }
}