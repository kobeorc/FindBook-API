<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Book
 *
 * @property-read Collection|Category[] $categories
 * @property-read Collection|Creator[] $creators
 * @property-read Collection|User[] $favorite
 * @property-read mixed $archived_at
 * @property-read mixed $is_favorite
 * @property-read mixed $status
 * @property-read Collection|Image[] $images
 * @property-read Collection|User[] $users
 * @method static bool|null forceDelete()
 * @method static Builder|Book isActive()
 * @method static Builder|Book isArchived()
 * @method static Builder|Book newModelQuery()
 * @method static Builder|Book newQuery()
 * @method static \Illuminate\Database\Query\Builder|Book onlyTrashed()
 * @method static Builder|Book query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|Book withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Book withoutTrashed()
 * @mixin Eloquent
 */
class Book extends Model
{
    const DEFAULT_LATITUDE = '0.00';
    const DEFAULT_LONGITUDE = '0.00';

    const STATUS_ARCHIVED = 'archived';
    const STATUS_ACTIVE = 'active';

    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'books';

    protected $fillable = [
        'name',
        'description',
        'year',
        'latitude',
        'longitude',
        'address',
    ];

    protected $appends = [
        'archived_at',
        'status',
        'is_favorite',
    ];

    protected $casts = [
        'name'        => 'string',
        'description' => 'string',
        'latitude'    => 'float',
        'longitude'   => 'float',
        'year'        => 'integer',
        'address'     => 'string',
    ];

    protected $hidden = [
        'deleted_at',
        'pivot',
        'created_at',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /** Relations */

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'books_have_creators');
    }

    public function authors()
    {
        return $this->creators()->whereType(Creator::TYPE_AUTHOR);
    }

    public function publishers()
    {
        return $this->creators()->whereType(Creator::TYPE_PUBLISHER);
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class, 'books_have_categories');
    }

    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_have_books')->withPivot('archived_at');
    }

    public function favorite()
    {
        return $this->belongsToMany(User::class, 'users_have_favorites');
    }

    /** Mutators */

    public function getArchivedAtAttribute()
    {
        return $this->pivot->archived_at ?? false;
    }

    public function getStatusAttribute()
    {
        return $this->archived_at ? self::STATUS_ARCHIVED : self::STATUS_ACTIVE;
    }

    public function getIsFavoriteAttribute()
    {
        return $this->favorite()->whereUserId(Auth::user()->id)->exists();
    }

    /** Scopes */

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeIsArchived(Builder $query)
    {
        $pivot = $this->users()->getTable();

        return $query->whereHas('users', function ($q) use ($pivot) {
            $q->whereNotNull("{$pivot}.archived_at");
        });
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeIsActive(Builder $query)
    {
        $pivot = $this->users()->getTable();

        return $query->whereHas('users', function ($q) use ($pivot) {
            $q->whereNull("{$pivot}.archived_at");
        });
    }
}
