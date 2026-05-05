<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'article_id',
        'tenant_category_id',
        'name',
        'description',
        'image',
        'price',
        'stock',
        'overrides',
        'status',
    ];

    protected $casts = [
        'overrides' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /*
    |--------------------------------
    | RELATIONS
    |--------------------------------
    */

    // 🏢 SaaS tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // 📦 Source article (catalog)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // 🗂️ Category inside tenant scope
    public function tenantCategory()
    {
        return $this->belongsTo(TenantCategorie::class);
    }

    // 🧩 Variants (REAL selling units)
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /*
    |--------------------------------
    | HELPERS (IMPORTANT)
    |--------------------------------
    */

    // 🧠 get effective price (fallback logic)
    public function getEffectivePriceAttribute()
    {
        return $this->price ?? 0;
    }

    // 🧠 get main image fallback
    public function getMainImageAttribute()
    {
        return $this->image ?? $this->article?->image;
    }

    // 🧠 total stock from variants
    public function getTotalStockAttribute()
    {
        return $this->variants()->sum('stock');
    }

    /*
    |--------------------------------
    | SCOPES
    |--------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function syncFromArticle()
    {
        if (!$this->overrides['image']) {
            $this->image = $this->article->image;
        }
    }
}