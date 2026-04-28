<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'email',
        'phone',
        'city',
        'address',
        'tax_number',
        'status'
    ];

    public function products()
    {
        return $this
            ->belongsToMany(Product::class)
            ->withPivot('purchase_price');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
