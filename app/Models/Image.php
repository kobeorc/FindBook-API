<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Image extends Model
{
    public    $timestamps = true;
    protected $table      = 'images';
    protected $fillable   = [
        'path',
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    public function user(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'imageable');
    }

    public function books(): MorphToMany
    {
        return $this->morphedByMany(Book::class, 'imageable');
    }
}
