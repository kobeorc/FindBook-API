<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Image
 *
 * @property-read Collection|Book[] $books
 * @property-read mixed $path
 * @property-read Collection|User[] $user
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @mixin Eloquent
 */
class Image extends Model
{
    public    $timestamps = true;
    protected $table      = 'images';
    protected $fillable   = [
        'path',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'pivot',
    ];

    public function user(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'imageable');
    }

    public function books(): MorphToMany
    {
        return $this->morphedByMany(Book::class, 'imageable');
    }

    public function getPathAttribute()
    {
        return secure_asset($this->attributes['path']) ?? '';
    }
}
