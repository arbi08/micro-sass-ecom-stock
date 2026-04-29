<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // (Free / Basic / Pro)
        'slug',
        'price',
        'currency', // USD, MAD, EUR
        'billing_interval', // monthly, yearly
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
