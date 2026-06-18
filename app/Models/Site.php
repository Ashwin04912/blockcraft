<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function uiBlocks(): HasMany
    {
        return $this->hasMany(UiBlock::class);
    }

    /**
     * The public-facing client URL for this site.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('client.page', $this->slug);
    }
}
