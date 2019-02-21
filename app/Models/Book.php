<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'name' => 'string',
        'description' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
    ];

    protected $hidden = [
        'deleted_at',

    ];

}
