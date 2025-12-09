<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    protected $guarded = [];

    /**
     * Get all messages that belong to this chat room.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Get all users that are members of this chat room.
     *
     * The relationship uses the `chat_room_user` pivot table and exposes the
     * `last_read_at` timestamp for each user in the room.
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_room_user')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    /**
     * Get the user that created this chat room.
     *
     * @return BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
