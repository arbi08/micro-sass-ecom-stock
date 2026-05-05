<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'brand',
        'image',
        'images',
        'attributes',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /*
    |--------------------------------
    | RELATIONS
    |--------------------------------
    */

    // 🗂️ Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🧩 Article Variants (template combinations)
    public function variants()
    {
        return $this->hasMany(ArticleVariant::class);
    }

    // 🏪 Products (vendor copies)
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------
    | HELPERS
    |--------------------------------
    */

    // 🧠 check if article has variants
    public function hasVariants(): bool
    {
        return !empty($this->attributes);
    }

    // 🧠 generate label from attributes (optional)
    public function getAttributeLabels(): array
    {
        $labels = [];

        foreach ($this->attributes ?? [] as $key => $values) {
            $labels[$key] = is_array($values) ? implode(',', $values) : $values;
        }

        return $labels;
    }

    // 🧠 fallback image logic
    public function getMainImageAttribute()
    {
        return $this->image ?? ($this->images[0] ?? null);
    }

    /*
    |--------------------------------
    | SCOPES
    |--------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}