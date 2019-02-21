<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
