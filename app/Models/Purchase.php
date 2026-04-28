<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasTenant, HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'supplier_id',
        'warehouse_id',
        'reference', // 'PO-' . str_pad(Purchase::count() + 1, 6, '0', STR_PAD_LEFT)
        'total_amount',
        'status',
        'received_at'
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
