<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserAuthToken
 *
 * @property-read User $user
 * @method static Builder|UserAuthToken newModelQuery()
 * @method static Builder|UserAuthToken newQuery()
 * @method static Builder|UserAuthToken query()
 * @mixin Eloquent
 */
class UserAuthToken extends Model
{
    public    $timestamps = true;
    protected $table      = 'user_auth_tokens';
    protected $fillable   = [
        'token',
        'refresh_token',
    ];

    protected $casts = [
        'token'         => 'string',
        'refresh_token' => 'string',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $visible = [
        'token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
