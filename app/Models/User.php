<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $casts  = [
        'name'     => 'string',
        'email'    => 'string',
        'password' => 'string',
        'role'     => 'string',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function auth_token(): HasMany
    {
        return $this->hasMany(UserAuthToken::class);
    }

    public function inventory(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'users_have_books');
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function avatar(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }
}
