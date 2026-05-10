<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttribute extends Model
{
    protected $fillable = ['name', 'wc_attribute_id'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_attribute')
            ->withPivot('value', 'wc_term_id');
    }
}
