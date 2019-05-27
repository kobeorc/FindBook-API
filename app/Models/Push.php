<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Push
 *
 * @method static Builder|Push newModelQuery()
 * @method static Builder|Push newQuery()
 * @method static Builder|Push query()
 * @mixin Eloquent
 */
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
