<?php

namespace App\Livewire\Admin;

use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin component for managing chat rooms and their members.
 *
 * Allows administrators to create rooms, edit room membership,
 * and assign users to specific rooms.
 */
class RoomsIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public string $name = '';

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
            isPrivate: false,
        );

        $this->reset(['name', 'selectedUsers']);
        $this->editingRoomId = $room->id;
    }

    /**
     * Load a room for editing its members.
     *
     * Populates the form with the room's current name, privacy setting,
     * and member list. Only allows editing public rooms, not private DMs.
     *
     * @param int $roomId ID of the room to edit.
     * @return void
     */
    public function editRoom(int $roomId): void
    {
        $room = ChatRoom::with('users')->findOrFail($roomId);

        if ($room->is_private) {
            session()->flash('error', 'Não é possível editar membros de salas privadas (DMs).');
            return;
        }

        $this->editingRoomId = $roomId;
        $this->name = $room->name ?? '';
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

        $this->reset(['editingRoomId', 'name', 'selectedUsers']);
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
        $this->reset(['editingRoomId', 'name', 'selectedUsers']);
    }

    /**
     * Delete a public room.
     *
     * Only allows deleting public rooms (not private DMs).
     * Displays success or error message accordingly.
     *
     * @param int $roomId ID of the room to delete.
     * @return void
     */
    public function deleteRoom(int $roomId): void
    {
        $room = ChatRoom::findOrFail($roomId);

        // Prevent deleting private rooms (DMs)
        if ($room->is_private) {
            session()->flash('error', 'Não é possível apagar salas privadas (DMs).');
            return;
        }

        $roomName = $room->name;
        $room->delete();

        session()->flash('status', "Sala '{$roomName}' apagada com sucesso!");
    }

    /**
     * Render the admin rooms management view.
     *
     * Returns the view with a paginated list of all rooms, including member count
     * and all available users.
     *
     * @return View
     */
    public function render(): View
    {
        $rooms = ChatRoom::withCount('users')->orderBy('name')->paginate(10);
        $rooms->setPath(route('admin.rooms'));

        return view('livewire.admin.rooms-index', [
            'rooms' => $rooms,
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
