<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use Illuminate\View\View;
use Livewire\Component;

class Room extends Component
{
    public ChatRoom $room;

    /**
     * When a new message is created, refresh the message list.
     *
     * @var array<int, string>
     */
    protected $listeners = [
        'messageCreated' => '$refresh',
    ];

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
        ]);
    }
}
