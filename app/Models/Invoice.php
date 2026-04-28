<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasTenant, HasFactory;
    
    protected $fillable = [
        'tenant_id',
        'order_id',
        'invoice_number',
        'subtotal',
        'tax',
        'total',
        'status',
        'issued_at',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
