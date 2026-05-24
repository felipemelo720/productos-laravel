<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductTag extends Model
{
    protected $fillable = ['name', 'wc_tag_id'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_tags_map', 'tag_id', 'product_id');
    }
}
