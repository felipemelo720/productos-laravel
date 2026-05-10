<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ActivityLog;

class ProductObserver
{
    public function created(Product $product): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->full_name ?? 'Unknown',
            'action' => 'create',
            'entity_type' => 'Product',
            'entity_id' => $product->id,
            'entity_name' => $product->name,
            'ip_address' => request()->ip(),
        ]);
    }

    public function updated(Product $product): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->full_name ?? 'Unknown',
            'action' => 'update',
            'entity_type' => 'Product',
            'entity_id' => $product->id,
            'entity_name' => $product->name,
            'details' => $product->getChanges(),
            'ip_address' => request()->ip(),
        ]);
    }

    public function deleted(Product $product): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->full_name ?? 'Unknown',
            'action' => 'delete',
            'entity_type' => 'Product',
            'entity_id' => $product->id,
            'entity_name' => $product->name,
            'ip_address' => request()->ip(),
        ]);
    }
}
