<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_have_categories');
    }
}
