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
        'regular_price', 'sale_price', 'sku', 'brand',
        'custom_tags', 'status', 'type', 'wc_product_id',
        'created_by', 'internal_observation'
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
        return $this->belongsToMany(ProductCategory::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_attribute')
            ->withPivot('value', 'wc_term_id');
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
