<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'total',
        'status'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
