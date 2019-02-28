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
    const STATUS_REGULAR = 'regular';
    const STATUS_STAR = 'star';
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $casts = [
        'name'     => 'string',
        'email'    => 'string',
        'password' => 'string',
        'role'     => 'string',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'avatar',
    ];

    public function auth_token(): HasMany
    {
        return $this->hasMany(UserAuthToken::class);
    }

    public function inventory(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'users_have_books')->withPivot(['archived_at']);
    }

    public function avatar(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    public function favorite()
    {
        return $this->belongsToMany(Book::class,'users_have_favorites');
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function getAvatarAttribute()
    {
        return $this->avatar()->exists() ? asset($this->avatar()->orderByDesc('id')->first()->path) : null;
    }
}
