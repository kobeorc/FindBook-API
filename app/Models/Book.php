<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'latitude',
        'longitude',
    ];

    protected $appends = [
        'archived_at',
        'status',
    ];

    protected $casts = [
        'name'        => 'string',
        'description' => 'string',
        'latitude'    => 'string',
        'longitude'   => 'string',
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

    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class, 'books_have_creators');
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class, 'books_have_categories');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_have_books')->withPivot('archived_at');
    }

    public function getArchivedAtAttribute()
    {
        return $this->pivot->archived_at ?? false;
    }

    public function getStatusAttribute()
    {
        return $this->archived_at ? self::STATUS_ARCHIVED : self::STATUS_ACTIVE;
    }

}
