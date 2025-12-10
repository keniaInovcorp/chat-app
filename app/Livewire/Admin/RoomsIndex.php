<?php

namespace App\Livewire\Admin;

use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Admin component for managing chat rooms and their members.
 *
 * Allows administrators to create rooms, edit room membership,
 * and assign users to specific rooms.
 */
class RoomsIndex extends Component
{
    public string $name = '';

    public bool $is_private = false;

    public array $selectedUsers = [];

    public ?int $editingRoomId = null;

    /**
     * Create a new chat room with the selected users.
     *
     * After creation, the form is reset and the new room is set for editing
     * to allow immediate member management.
     *
     * @param ChatService $service Service responsible for room creation.
     * @return void
     */
    public function createRoom(ChatService $service): void
    {
        $this->validate([
            'name' => 'required|string|min:3',
        ]);

        $room = $service->createRoom(
            name: $this->name,
            creator: $this->user(),
            userIds: $this->selectedUsers,
            isPrivate: $this->is_private,
        );

        $this->reset(['name', 'is_private', 'selectedUsers']);
        $this->editingRoomId = $room->id;
    }

    /**
     * Load a room for editing its members.
     *
     * Populates the form with the room's current name, privacy setting,
     * and member list.
     *
     * @param int $roomId ID of the room to edit.
     * @return void
     */
    public function editRoom(int $roomId): void
    {
        $this->editingRoomId = $roomId;

        $room = ChatRoom::with('users')->findOrFail($roomId);

        $this->name = $room->name ?? '';
        $this->is_private = $room->is_private;
        $this->selectedUsers = $room->users->pluck('id')->toArray();
    }

    /**
     * Save the updated member list for the room being edited.
     *
     * Updates the room's membership with the selected users and displays
     * a success message. Resets the form after saving.
     *
     * @param ChatService $service Service responsible for managing room membership.
     * @return void
     */
    public function saveMembership(ChatService $service): void
    {
        if (! $this->editingRoomId) {
            return;
        }

        $room = ChatRoom::findOrFail($this->editingRoomId);

        $service->assignUsersToRoom($room, $this->user(), $this->selectedUsers);

        session()->flash('status', 'Membros atualizados com sucesso!');

        $this->reset(['editingRoomId', 'name', 'is_private', 'selectedUsers']);
    }

    /**
     * Cancel the current room editing operation.
     *
     * Resets all form fields and exits edit mode.
     *
     * @return void
     */
    public function cancelEdit(): void
    {
        $this->reset(['editingRoomId', 'name', 'is_private', 'selectedUsers']);
    }

    /**
     * Render the admin rooms management view.
     *
     * Returns the view with a list of all rooms (including member count)
     * and all available users.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.rooms-index', [
            'rooms' => ChatRoom::withCount('users')->orderBy('name')->get(),
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
}
