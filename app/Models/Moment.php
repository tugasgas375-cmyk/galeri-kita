<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Moment extends Model
{
    protected $fillable = ['title', 'description', 'tags', 'moment_date', 'views'];

    protected $casts = [
        'moment_date' => 'date',
        'views' => 'integer',
        'tags' => 'array',
    ];

    public static function allTags(): array
    {
        return static::query()
            ->pluck('tags')
            ->filter()
            ->flatten()
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->map(fn ($tag) => mb_strtolower($tag))
            ->unique()
            ->values()
            ->all();
    }

    public function scopeFilterTag($query, ?string $tag)
    {
        if (! $tag) {
            return $query;
        }

        return $query->whereJsonContains('tags', mb_strtolower(trim($tag)));
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('sort_order')->orderBy('id');
    }

    public function coverPhoto(): ?Photo
    {
        return $this->photos()->orderByDesc('is_cover')->orderBy('sort_order')->orderBy('id')->first();
    }
}
