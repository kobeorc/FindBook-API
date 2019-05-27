<?php

namespace App\Models\Chat;

use App\Models\User;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Chat\ChatMessage
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage newQuery()
 * @method static Builder|ChatMessage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage query()
 * @method static bool|null restore()
 * @method static Builder|ChatMessage withTrashed()
 * @method static Builder|ChatMessage withoutTrashed()
 * @mixin Eloquent
 *
 * @property integer $id
 * @property-read integer $chat_id
 * @property string $type
 * @property string $status
 * @property string $text
 */
class ChatMessage extends Model
{
    use SoftDeletes;

    const TYPES = [
        self::TYPE_AUDIO,
        self::TYPE_TEXT,
        self::TYPE_AUDIO,
        self::TYPE_FILE,
        self::TYPE_COMBINED,
        self::TYPE_FORWARD,
        self::TYPE_REPLY,
    ];
    const TYPE_TEXT = 'text';
    const TYPE_AUDIO = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_FILE = 'file';
    const TYPE_IMAGE = 'image';
    const TYPE_COMBINED = 'combined';
    const TYPE_FORWARD = 'forward';
    const TYPE_REPLY = 'reply';

    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_READ = 'read';

    public $timestamps = true;
    protected $table = 'chat_messages';

    protected $fillable = [
        'type',
        'status',
        'text',
    ];

    protected $hidden = [
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

}