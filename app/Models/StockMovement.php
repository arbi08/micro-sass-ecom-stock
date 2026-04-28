<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'product_id',
        'warehouse_id',
        'type',
        'quantity'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
