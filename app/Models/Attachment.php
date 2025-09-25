<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'type',
        'path',
        'meta',
    ];
    protected $casts = [
        'meta' => 'array',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
