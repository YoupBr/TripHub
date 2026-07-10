<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'trip_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'location',
        'category',
        'location',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
