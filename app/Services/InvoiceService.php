<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function createFromOrder(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $tenantId = app('tenant')->id;

            $invoice = Invoice::create([
                'tenant_id' => $tenantId,
                'order_id' => $order->id,
                'invoice_number' => $this->generateNumber(),
                'subtotal' => $order->total,
                'tax' => 0,
                'total' => $order->total,
                'status' => 'issued',
                'issued_at' => now(),
            ]);

            foreach ($order->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total' => $item->quantity * $item->price,
                ]);
            }

            return $invoice;
        });
    }

    private function generateNumber()
    {
        return 'FAC-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT);
    }
}