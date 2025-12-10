<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public ?ChatRoom $activeRoom = null;
    public string $newRoomName = '';

    /**
     * Initialize the component, setting the first user chat room as active if any.
     */
    public function mount(): void
    {
        $this->activeRoom = $this->user()->chatRooms()->first();
    }

    /**
     * Select an existing room to become the active room.
     *
     * @param  int  $roomId  Identifier of the room to activate.
     * @return void
     */
    public function selectRoom(int $roomId): void
    {
        $room = $this->user()->chatRooms()
            ->where('chat_rooms.id', $roomId)
            ->firstOrFail();

        $this->activeRoom = $room;
    }

    /**
     * Create a new chat room for the currently authenticated user.
     *
     * @param  ChatService  $service  Service responsible for chat room creation.
     * @return void
     */
    public function createRoom(ChatService $service): void
    {
        $this->validate([
            'newRoomName' => 'required|string|min:3',
        ]);

        $room = $service->createRoom(
            name: $this->newRoomName,
            creator: $this->user()
        );

        $this->newRoomName = '';
        $this->activeRoom = $room;
    }

    /**
     * Start or resume a direct message (DM) conversation with another user.
     *
     * @param  int  $userId  ID of the user to start the DM with.
     * @return void
     */
    public function startDm(int $userId): void
    {
        if ($userId === $this->userId()) {
            return;
        }

        $service = app(ChatService::class);
        $room = $service->getDirectRoom($this->user(), User::findOrFail($userId));

        if (! $room->users()->where('users.id', $this->userId())->exists()) {
            abort(403);
        }

        $this->activeRoom = $room;
    }

    /**
     * Render the Livewire component view (used as a nested component inside the dashboard layout).
     *
     * @return View
     */
    public function render(): View
    {
        $user = $this->user();

        return view('livewire.chat.index', [
            'rooms' => $user->chatRooms()->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
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

    /**
     * Get the ID of the authenticated user.
     *
     * @return int
     */
    protected function userId(): int
    {
        return (int) Auth::id();
    }
}
