<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use App\Services\ChatService;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Input extends Component
{
    public ChatRoom $room;
    public string $body = '';

    public function sendMessage(ChatService $service): void
    {
        $this->validate([
            'body' => 'required|string',
        ]);

        $messageText = $this->body;

        $service->sendMessage(room: $this->room, sender: $this->user(), message: $messageText);

        $this->reset('body');

        // Dispatch event to trigger automatic scroll in the Room component
        $this->dispatch('message-sent');

        $this->dispatch('input-cleared');
    }

    public function render()
    {
        return view('livewire.chat.input');
    }

    /**
     * Get the authenticated user instance.
     *
     * @return User
     */
    protected function user(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }
}
