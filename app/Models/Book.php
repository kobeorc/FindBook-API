<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'books';

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'name'        => 'string',
        'description' => 'string',
        'latitude'    => 'string',
        'longitude'   => 'string',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function images(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function creators(): BelongsToMany
    {
        return $this->belongsToMany(Creator::class,'books_have_creators');
    }

    public function categories(): belongsToMany
    {
        return $this->belongsToMany(Category::class, 'books_have_categories');
    }

}
