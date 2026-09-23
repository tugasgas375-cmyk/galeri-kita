<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Moment extends Model
{
    protected $fillable = ['title', 'description', 'moment_date', 'views'];

    protected $casts = [
        'moment_date' => 'date',
        'views' => 'integer',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('sort_order')->orderBy('id');
    }

    public function coverPhoto(): ?Photo
    {
        return $this->photos()->orderByDesc('is_cover')->orderBy('sort_order')->orderBy('id')->first();
    }
}
