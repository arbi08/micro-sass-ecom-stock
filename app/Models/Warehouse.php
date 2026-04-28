<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasTenant, HasFactory;

     protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'city',
        'address',
        'manager_id',
        'capacity',
        'lat',
        'lng',
        'is_main'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
