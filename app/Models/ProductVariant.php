<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'article_variant_id',
        'attributes',
        'price',
        'stock',
        'image',
        'overrides',
        'sku',
        'status',
    ];

    protected $casts = [
        'attributes' => 'array',
        'overrides' => 'array',
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function articleVariant()
    {
        return $this->belongsTo(ArticleVariant::class);
    }
}
