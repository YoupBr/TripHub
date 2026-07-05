<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    protected $fillable = [
        'trip_id',
        'title',
        'is_done',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}