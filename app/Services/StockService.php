<?php

namespace App\Services;

use App\Models\Stock;

class StockService
{
    public function increase($productId, $warehouseId, $qty)
    {
        $tenantId = app('tenant')->id;
        
        $stock = Stock::firstOrCreate([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'tenant_id' => $tenantId,
        ]);

        $stock->quantity += $qty;
        $stock->save();

        $this->log($productId, $warehouseId, 'in', $qty);
    }

    public function decrease($productId, $warehouseId, $qty)
    {
        $tenantId = app('tenant')->id;
        
        $stock = Stock::where([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'tenant_id' => $tenantId,
        ])->first();

        if (!$stock || $stock->quantity < $qty) {
            abort(400, 'Not enough stock');
        }

        $stock->quantity -= $qty;
        $stock->save();

        $this->log($productId, $warehouseId, 'out', $qty);
    }

    private function log($productId, $warehouseId, $type, $qty)
    {
        $tenantId = app('tenant')->id;
        
        \App\Models\StockMovement::create([
            'tenant_id' => $tenantId,
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'type' => $type,
            'quantity' => $qty,
        ]);
    }
}