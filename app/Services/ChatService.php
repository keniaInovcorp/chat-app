<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Str;

class ChatService
{
    /**
     * Create a new room.
     *
     * Rules:
     * - Any authenticated user can create a room.
     * - ADMIN: can optionally add other users ($userIds).
     * - Regular USER: room starts only with themselves as a member.
     */
    public function createRoom(string $name, User $creator, array $userIds = [], bool $isPrivate = false): ChatRoom
    {
        $room = ChatRoom::create([
            'name'       => $name,
            'created_by' => $creator->id,
            'is_private' => $isPrivate,
            'reference'  => Str::slug($name) . '-' . Str::random(6),
        ]);

        if ($creator->isAdmin()) {
            $room->users()->sync(
                array_unique(array_merge([$creator->id], $userIds))
            );
        } else {
            $room->users()->sync([$creator->id]);
        }

        return $room;
    }

    /**
     * Manage room membership.
     *
     * Only ADMIN users can change members.
     */
    public function assignUsersToRoom(ChatRoom $room, User $admin, array $userIds): void
    {
        if (! $admin->isAdmin()) {
            abort(403, 'Apenas administradores podem gerir membros das salas.');
        }

        $room->users()->sync($userIds);
    }

    /**
     * Send a message to a room where the user is a member.
     * Creates notifications for all room members except the sender.
     */
    public function sendMessage(ChatRoom $room, User $sender, string $message, ?string $attachment = null): ChatMessage
    {
        if (! $room->users()->where('users.id', $sender->id)->exists()) {
            abort(403);
        }

        $chatMessage = ChatMessage::create([
            'chat_room_id' => $room->id,
            'user_id'      => $sender->id,
            'body'         => $message,
            'attachment'   => $attachment,
        ]);

        // Create notifications for all room members except the sender
        $roomMembers = $room->users()->where('users.id', '!=', $sender->id)->pluck('users.id');
        
        foreach ($roomMembers as $memberId) {
            Notification::create([
                'user_id' => $memberId,
                'chat_room_id' => $room->id,
                'chat_message_id' => $chatMessage->id,
            ]);
        }

        return $chatMessage;
    }

    /**
     * Get or create a private room (DM) between two users.
     *
     * Any user can initiate a DM.
     */
    public function getDirectRoom(User $user1, User $user2): ChatRoom
    {
        $room = ChatRoom::where('is_private', true)
            ->whereHas('users', fn($q) => $q->where('users.id', $user1->id))
            ->whereHas('users', fn($q) => $q->where('users.id', $user2->id))
            ->first();

        if ($room) {
            return $room;
        }

        // Create new DM room with both users
        $room = ChatRoom::create([
            'name'       => $user1->name . ' & ' . $user2->name,
            'created_by' => $user1->id,
            'is_private' => true,
            'reference'  => 'dm-' . Str::random(10),
        ]);

        // Always add both users to DM rooms, regardless of role
        $room->users()->sync([$user1->id, $user2->id]);

        return $room;
    }
}


