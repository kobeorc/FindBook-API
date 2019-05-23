<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    protected $table = 'push';

    const STATUS_PENDING = 'pending';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'ids',
        'count',
    ];

}
