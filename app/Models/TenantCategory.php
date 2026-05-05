<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TenantCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tenant_categories';

    protected $fillable = [
        'tenant_id',
        'name',
        'category_id',
        'parent_id',
        'slug',
        'icon',
        'sort_order',
        'description',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    /*
    |----------------------------
    | Relations
    |----------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /*
    |----------------------------
    | Scopes
    |----------------------------
    */

    public function scopeTypes($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeCategories($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /*
    |----------------------------
    | Helpers
    |----------------------------
    */

    public function getLevelAttribute()
    {
        if (is_null($this->parent_id)) {
            return 'type';
        }

        return $this->parent?->parent_id ? 'subcategory' : 'category';
    }

    /*
    |----------------------------
    | Boot (slug auto generate)
    |----------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = self::generateSlug($category->name, $category->tenant_id);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = self::generateSlug($category->name, $category->tenant_id);
            }
        });
    }

    public static function generateSlug($name, $tenantId)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            self::where('tenant_id', $tenantId)
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }
}