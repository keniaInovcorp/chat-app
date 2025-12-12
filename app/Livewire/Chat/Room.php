<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Room extends Component
{
    public ChatRoom $room;

    /**
     * When a new message is created, refresh the message list and mark notifications as read.
     *
     * @var array<int, string>
     */
    protected $listeners = [
        'message-sent' => 'handleMessageSent',
    ];

    /**
     * Initialize the component and mark room notifications as read.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->markRoomNotificationsAsRead();
    }

    /**
     * Handle when a new message is sent in this room.
     * Refreshes the component and marks notifications as read.
     *
     * @return void
     */
    public function handleMessageSent(): void
    {
        $this->markRoomNotificationsAsRead();
        $this->dispatch('$refresh');
    }

    /**
     * Mark all unread notifications for this room as read.
     *
     * @return void
     */
    protected function markRoomNotificationsAsRead(): void
    {
        Notification::where('user_id', Auth::id())
            ->where('chat_room_id', $this->room->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

     /**
     * Render the chat room view with the latest messages.
     *
     * @return View
     */
    public function render()
    {
        $messages = $this->room->messages()
            ->with('sender')
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return view('livewire.chat.room', [
            'messages' => $messages,
            'members' => $this->room->users,
        ]);
    }
}
