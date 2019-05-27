<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_CLOSED = 'closed';
    protected $table = 'push';
    protected $fillable = [
        'ids',
        'count',
    ];

    protected $casts = [
        'ids' => 'array',
        'count' => 'integer',
    ];

}
