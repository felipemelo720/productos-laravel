<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'user_name', 'action', 'entity_type',
        'entity_id', 'entity_name', 'details', 'ip_address'
    ];

    protected $casts = ['details' => 'json'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
