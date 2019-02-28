<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_have_creators');
    }
}
