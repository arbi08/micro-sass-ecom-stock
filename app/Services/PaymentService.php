<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function payOrder(Order $order, array $data)
    {
        return DB::transaction(function () use ($order, $data) {
            $tenantId = app('tenant')->id;

            $payment = Payment::create([
                'tenant_id' => $tenantId,
                'order_id' => $order->id,
                'payment_method' => $data['method'],
                'amount' => $order->total,
                'currency' => 'MAD',
                'status' => 'paid',
                'transaction_reference' => $data['reference'] ?? null,
                'paid_at' => now(),
            ]);

            // update order
            $order->update([
                'status' => 'paid'
            ]);

            return $payment;
        });
    }
}