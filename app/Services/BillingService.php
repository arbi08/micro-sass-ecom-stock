<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Subscription;

class BillingService
{
    public function subscribe(Tenant $tenant, Plan $plan)
    {
        return DB::transaction(function () use ($tenant, $plan) {

            // 💳 create payment
            $payment = Payment::create([
                'tenant_id' => $tenant->id,
                'amount' => $plan->price,
                'status' => 'paid',
            ]);

            // 🧾 create subscription
            $subscription = Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
            ]);

            return $subscription;
        });
    }
}