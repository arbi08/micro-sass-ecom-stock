<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleVariant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'article_id',
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

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
