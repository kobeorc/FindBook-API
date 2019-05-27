<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Creator
 *
 * @property-read Collection|Book[] $books
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Creator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Creator newQuery()
 * @method static Builder|Creator onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Creator query()
 * @method static bool|null restore()
 * @method static Builder|Creator withTrashed()
 * @method static Builder|Creator withoutTrashed()
 * @mixin Eloquent
 */
class Creator extends Model
{
    const TYPE_AUTHOR = 'author';
    const TYPE_PUBLISHER = 'publisher';
    use SoftDeletes;

    public    $timestamps = true;
    protected $table      = 'creators';
    protected $fillable   = [
        'full_name',
        'type',
    ];
    protected $hidden     = [
        'updated_at',
        'created_at',
        'deleted_at',
        'pivot',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_have_creators');
    }
}
