<?php

namespace App\Models\Chat;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Chat\Chat
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat newQuery()
 * @method static Builder|Chat onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Chat query()
 * @method static bool|null restore()
 * @method static Builder|Chat withTrashed()
 * @method static Builder|Chat withoutTrashed()
 * @mixin Eloquent
 *
 * @property integer $id
 * @property string $type
 *
 */
class Chat extends Model
{
    use SoftDeletes;

    const TYPES = [
        self::TYPE_PRIVATE,
        self::TYPE_GROUP,
        self::TYPE_CHANNEL,
    ];
    const TYPE_PRIVATE = 'private';
    const TYPE_CHANNEL = 'channel';
    const TYPE_GROUP = 'group';

    public $timestamps = true;
    protected $table = 'chats';

    protected $fillable = [
        'type',
    ];

    protected $hidden = [
        'pivot',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'id', 'chat_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_have_chats', 'chat_id', 'user_id')->withTimestamps();
    }
}