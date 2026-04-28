<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addresse extends Model
{
    use HasTenant, HasFactory, SoftDeletes;
    
    protected $fillable = [
        'tenant_id',
        'user_id',
        'full_name',
        'phone',
        'city',
        'address_line',
        'postal_code',
        'is_default',
        'type',
        'label',
        'lat',
        'lng',
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
    
}
