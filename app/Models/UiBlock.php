<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UiBlock extends Model
{
    protected $fillable = [
        'site_id',
        'title',
        'type',
        'is_active',
        'display_order',
        'config',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'config'        => 'array',
        'display_order' => 'integer',
        'site_id'       => 'integer',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** Scope: only active blocks. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Scope: ordered by display_order ascending. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order');
    }
}
