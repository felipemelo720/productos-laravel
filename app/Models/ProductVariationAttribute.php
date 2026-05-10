<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationAttribute extends Model
{
    protected $fillable = ['product_variation_id', 'attribute_name', 'attribute_value', 'wc_term_id'];

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
