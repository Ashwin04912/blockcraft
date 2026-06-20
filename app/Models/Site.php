<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'owner_id'];

    public function uiBlocks(): HasMany
    {
        return $this->hasMany(UiBlock::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The public-facing client URL for this site.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('client.page', $this->slug);
    }
}
