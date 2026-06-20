<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'owner_id', 'background_color'];

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

    /**
     * Curated swatches offered for the page background picker. Each entry
     * carries its own contrast text class so we don't need to compute
     * luminance at render time — just look up the stored hex.
     */
    public static function backgroundPalette(): array
    {
        return [
            ['label' => 'White',      'value' => '#ffffff', 'text' => 'text-dark'],
            ['label' => 'Off White',  'value' => '#f8f9fa', 'text' => 'text-dark'],
            ['label' => 'Light Grey', 'value' => '#e9ecef', 'text' => 'text-dark'],
            ['label' => 'Grey',       'value' => '#adb5bd', 'text' => 'text-dark'],
            ['label' => 'Dark Grey',  'value' => '#495057', 'text' => 'text-light'],
            ['label' => 'Charcoal',   'value' => '#212529', 'text' => 'text-light'],
            ['label' => 'Black',      'value' => '#000000', 'text' => 'text-light'],
            ['label' => 'Cream',      'value' => '#fdf6ec', 'text' => 'text-dark'],
            ['label' => 'Light Blue', 'value' => '#eef3fb', 'text' => 'text-dark'],
            ['label' => 'Sand',       'value' => '#f5f0e6', 'text' => 'text-dark'],
        ];
    }

    /** Bootstrap text-contrast class to use against this site's background_color. */
    public function getBackgroundTextClassAttribute(): string
    {
        foreach (self::backgroundPalette() as $swatch) {
            if ($swatch['value'] === $this->background_color) {
                return $swatch['text'];
            }
        }

        return 'text-dark';
    }
}
