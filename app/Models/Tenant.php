<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Tenant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'status',
        'plan'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id')->withDefault();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
    
    public function addresses()
    {
        return $this->hasMany(Addresse::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function categories()
    {
        return $this->hasMany(TenantCategory::class);
    }

    public function scopeCurrent($query)
    {
        return $query->where('tenant_id', app('tenant_id'));
    }

    public function isOwner($userId)
    {
        return $this->owner_id === $userId;
    }

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $tenant->slug = Str::slug($tenant->name) . '-' . uniqid();
        });
    }
}


