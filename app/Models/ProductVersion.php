<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVersion extends Model
{
    protected $fillable = [
        'product_id', 'version_number', 'name', 'slug', 'short_description',
        'description', 'regular_price', 'sale_price', 'sku', 'brand', 'status',
        'type', 'custom_tags', 'internal_observation', 'categories_json',
        'attributes_json', 'images_json', 'variations_json', 'wc_tags_json',
        'change_type', 'change_description', 'created_by'
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'categories_json' => 'array',
        'attributes_json' => 'array',
        'images_json' => 'array',
        'variations_json' => 'array',
        'wc_tags_json' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
