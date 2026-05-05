<?php

namespace App\Models;

use App\Models\Scopes\HasTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasTenant, HasFactory, SoftDeletes;

    protected $appends = ['level'];
    protected $hidden = ['parent'];

    protected $fillable = [
        'tenant_id',
        'name',
        'parent_id',
        'slug',
        'icon',
        'sort_order',
        'description',
        'meta_title',
        'meta_description',
        'is_active'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Type = root

    public function scopeTypes($query)
    {
        return $query->whereNull('parent_id');
    }

    // Tenant filter
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Categories (children)
    public function scopeCategories($query)
    {
        return $query
            ->whereNotNull('parent_id')
            ->whereIn('parent_id', function ($q) {
                $q
                    ->select('id')
                    ->from('categories')
                    ->whereNull('parent_id');
            });
    }

    public function scopeSubCategories($query)
    {
        return $query
            ->whereNotNull('parent_id')
            ->whereIn('parent_id', function ($q) {
                $q
                    ->select('id')
                    ->from('categories')
                    ->whereNotNull('parent_id')
                    ->whereIn('parent_id', function ($q2) {
                        $q2
                            ->select('id')
                            ->from('categories')
                            ->whereNull('parent_id');
                    });
            });
    }

    public function isCategory()
    {
        return !is_null($this->parent_id) && is_null($this->parent?->parent_id);
    }

    public function isSubCategory()
    {
        return !is_null($this->parent_id) && !is_null($this->parent?->parent_id);
    }

    // helper
    public function isType()
    {
        return is_null($this->parent_id);
    }

    public function getLevelAttribute()
    {
        if ($this->isType())
            return 'type';
        if ($this->isCategory())
            return 'category';
        return 'subcategory';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = self::generateUniqueSlug($category->name, $category->tenant_id);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = self::generateUniqueSlug($category->name, $category->tenant_id);
            }
        });
    }

    public static function generateUniqueSlug($name, $tenantId)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        $existing = self::where('tenant_id', $tenantId)
            ->where('slug', 'like', $slug . '%')
            ->pluck('slug')
            ->toArray();

        while (in_array($slug, $existing)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function tenants()
    {
        return $this->belongsToMany(
            Tenant::class,
            'tenant_categories'
        );
    }
}
