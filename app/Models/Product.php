<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'short_description', 'description',
        'regular_price', 'sale_price', 'sku', 'brand', 'wc_brand_id',
        'custom_tags', 'status', 'type', 'wc_product_id',
        'created_by', 'internal_observation',
        'wc_publication_status', 'wc_status_checked_at',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'product_categories_map', 'product_id', 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_tags_map', 'product_id', 'tag_id');
    }

    public function wcBrand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductBrand::class, 'wc_brand_id', 'wc_brand_id');
    }

    public function attributes(): HasMany
    {
        // Legacy schema: product_attributes is a per-product row table (name/value), not a pivot
        return $this->hasMany(ProductAttribute::class);
    }

    public function exportLogs(): HasMany
    {
        return $this->hasMany(ExportLog::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProductVersion::class)->orderByDesc('version_number');
    }

    public function scopeNotExported($query)
    {
        return $query->whereNull('wc_product_id');
    }

    public function scopeExported($query)
    {
        return $query->whereNotNull('wc_product_id');
    }

    public function scopeSimple($query)
    {
        return $query->where('type', 'simple');
    }

    public function scopeVariable($query)
    {
        return $query->where('type', 'variable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
