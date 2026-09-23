<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MomentLike extends Model
{
    public $timestamps = false;

    protected $fillable = ['moment_id', 'device_id', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function moment(): BelongsTo
    {
        return $this->belongsTo(Moment::class);
    }
}