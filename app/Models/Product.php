<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTenant, HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'cost_price',
        'barcode',
        'image',
        'status',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function suppliers()
    {
        return $this
            ->belongsToMany(Supplier::class)
            ->withPivot('purchase_price');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function mainCategory()
    {
        return $this
            ->belongsTo(Category::class, 'product_categories', 'product_id', 'category_id')
            ->where('product_categories.type', 'main');
    }

    public function subCategories()
    {
        return $this
            ->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->where('product_categories.type', 'sub');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeWithCategories($query)
    {
        return $query->with('categories');
    }

    public function scopeWithTenant($query)
    {
        return $query->with('tenant');
    }
}
