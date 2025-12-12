<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use App\Models\Notification;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public ?ChatRoom $activeRoom = null;
    public string $newRoomName = '';
    public string $userSearch = '';

    /**
     * Listen for events from other components.
     *
     * @var array<int, string>
     */
    protected $listeners = [
        'select-room' => 'selectRoom',
    ];

    /**
     * Initialize the component, setting the first user chat room as active if any.
     * If a room ID is provided in the request, select that room instead.
     */
    public function mount(): void
    {
        $roomId = request()->query('room');
        
        if ($roomId) {
            $this->selectRoom((int) $roomId);
        } else {
            $this->activeRoom = $this->user()->chatRooms()->first();
        }
    }

    /**
     * Select an existing room to become the active room.
     * Marks all notifications for this room as read.
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
        $this->markRoomNotificationsAsRead($roomId);
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
        $this->markRoomNotificationsAsRead($room->id);
    }

    /**
     * Start or resume a direct message (DM) conversation with another user.
     * Marks all notifications for this room as read.
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
        $this->markRoomNotificationsAsRead($room->id);
    }

    /**
     * Render the Livewire component view (used as a nested component inside the dashboard layout).
     *
     * @return View
     */
    public function render(): View
    {
        $user = $this->user();

        $usersQuery = User::orderBy('name');
        
        if (!empty($this->userSearch)) {
            $usersQuery->where('name', 'like', '%' . $this->userSearch . '%');
        }

        return view('livewire.chat.index', [
            'rooms' => $user->chatRooms()->with('users')->orderBy('name')->get(),
            'users' => $usersQuery->limit(6)->get(),
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

    /**
     * Mark all unread notifications for a specific room as read.
     *
     * @param  int  $roomId  ID of the room.
     * @return void
     */
    protected function markRoomNotificationsAsRead(int $roomId): void
    {
        Notification::where('user_id', $this->userId())
            ->where('chat_room_id', $roomId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
