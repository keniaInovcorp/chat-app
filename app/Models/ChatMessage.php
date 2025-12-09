<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $guarded = [];

    /**
     * Get the user who sent this message.
     *
     * @return BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the chat room that this message belongs to.
     *
     * @return BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(ChatRoom::class);
    }
}
