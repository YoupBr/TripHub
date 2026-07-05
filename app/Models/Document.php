<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'trip_id',
        'title',
        'file_path',
        'type',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}