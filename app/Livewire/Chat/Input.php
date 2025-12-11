<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use App\Services\ChatService;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\WithFileUploads;

/**
 * Livewire component for handling chat message input.
 *
 * This component allows users to send text messages and file attachments
 * to a chat room. It validates the input, stores attachments, and dispatches
 * events to update the UI.
 */
class Input extends Component
{
    use WithFileUploads;

    public ChatRoom $room;

    public string $body = '';

    public $attachment = null;

    /**
     * Send a message to the chat room.
     *
     * Validates the message body and/or attachment, stores the attachment
     * in the public disk, saves the message via ChatService, and dispatches
     * events to update the UI. At least one of body or attachment is required.
     *
     * @param ChatService $service Service responsible for sending messages.
     * @return void
     */
    public function sendMessage(ChatService $service): void
    {
        $this->validate([
            'body' => 'required_without:attachment|nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:2048',
        ]);

        $attachmentPath = null;

        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('chat-attachments', 'public');
        }

        $service->sendMessage(
            room: $this->room,
            sender: $this->user(),
            message: $this->body ?: '',
            attachment: $attachmentPath
        );

        $this->reset('body', 'attachment');

        // Dispatch event to trigger automatic scroll in the Room component
        $this->dispatch('message-sent');

        $this->dispatch('input-cleared');
    }

    /**
     * Render the chat input component view.
     *
     * @return View
     */
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
