<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasTenant, HasFactory, SoftDeletes;
    
    protected $fillable = [
        'tenant_id',
        'order_id',
        'warehouse_id',
        'address_id',
        'tracking_number',
        'status',
        'shipped_at',
        'delivered_at',
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    
    public function address()
    {
        return $this->belongsTo(Addresse::class);
    }
    
    public function items()
    {
        return $this->hasMany(ShipmentItem::class);
    }
}
