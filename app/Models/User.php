<?php

namespace App\Models;

use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property-read Collection|UserAuthToken[] $auth_token
 * @property-read Collection|Image[] $avatar
 * @property-read Collection|Book[] $favorite
 * @property-read Collection|User[] $followed
 * @property-read Collection|User[] $following
 * @property-read Collection|Book[] $inventory
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 *
 * @property integer $id
 */
class User extends Authenticatable
{
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_GUEST = 'guest';

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
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'role' => 'string',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
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

    public function favorite()
    {
        return $this->belongsToMany(Book::class, 'users_have_favorites');
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'users_have_chats', 'user_id', 'chat_id')->withTimestamps();
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

    public function avatar(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable');
    }

    /**
     * @return BelongsToMany
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscribers', 'leading_id', 'follower_id');
    }

    /**
     * @return BelongsToMany
     */
    public function followed(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subscribers', 'follower_id', 'leading_id');
    }

    public function messages()
    {
        return $this->belongsTo(ChatMessage::class, 'author_id', 'id');
    }
}
