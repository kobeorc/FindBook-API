<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Category
 *
 * @property-read Collection|Book[] $books
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static bool|null restore()
 * @method static Builder|Category withTrashed()
 * @method static Builder|Category withoutTrashed()
 * @mixin Eloquent
 */
class Category extends Model
{
    use SoftDeletes;

    public    $timestamps = true;
    protected $fillable   = [
        'name',
    ];
    protected $table      = 'categories';
    protected $hidden     = [
        'created_at',
//        'updated_at',
        'deleted_at',
        'pivot',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_have_categories');
    }
}
