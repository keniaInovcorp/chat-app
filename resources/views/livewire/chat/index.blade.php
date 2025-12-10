<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-100 text-black flex flex-col">
        <div class="flex-1 overflow-y-auto p-4 space-y-6">
            <!-- Rooms -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-black">
                        Salas
                    </h3>

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->isAdmin() && Route::has('admin.rooms'))
                            <a href="{{ route('admin.rooms') }}"
                               class="text-xs text-gray-500 hover:text-white underline">
                                Gerir
                            </a>
                        @endif
                    </div>
                </div>

                <ul class="space-y-1 mb-3">
                    @forelse($rooms as $room)
                        <li>
                            <button
                                wire:click="selectRoom({{ $room->id }})"
                                class="w-full text-left px-2 py-1 rounded transition
                                    {{ $activeRoom?->id === $room->id
                                        ? 'bg-gray-800 text-white'
                                        : 'hover:bg-gray-800 hover:text-white' }}"
                            >
                                # {{ $room->name ?? 'DM' }}
                            </button>
                        </li>
                    @empty
                        <li class="text-xs text-black">
                            Ainda não estás em nenhuma sala.
                        </li>
                    @endforelse
                </ul>

                <!-- Create new room -->
                <form wire:submit.prevent="createRoom" class="flex items-center gap-2">
                    <input
                        type="text"
                        wire:model="newRoomName"
                        placeholder="Nova sala..."
                        class="flex-1 px-2 py-1 text-xs bg-gray-900 border border-gray-700 rounded
                               focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    <button
                        type="submit"
                        class="text-xs px-2 py-1 bg-gray-200 text-black font-medium rounded border border-gray-400
                               hover:bg-gray-300 focus:outline-none
                               focus:ring-1 focus:ring-gray-500 focus:ring-offset-1 focus:ring-offset-gray-100"
                    >
                        Criar
                    </button>
                </form>
            </div>

            <!-- Users -->
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-black mb-2">
                    Pessoas
                </h3>
                <ul class="space-y-1">
                    @foreach($users as $user)
                        <li class="flex items-center space-x-2 px-2 py-1 text-sm text-black font-medium
                                   cursor-pointer hover:bg-gray-800 rounded"
                            wire:click="startDm({{ $user->id }})"
                        >
                            <span class="w-2 h-2 rounded-full
                                {{ $user->status === 'online' ? 'bg-green-500' : 'bg-gray-600' }}">
                            </span>
                            <span>{{ $user->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- User Profile Footer -->
        <div class="p-4 border-t border-gray-800">
            <div class="text-sm font-semibold text-black">
                {{ auth()->user()->name }}
            </div>
            <div class="text-xs text-black">
                {{ ucfirst(auth()->user()->status) }}
            </div>
        </div>
    </aside>

    <!-- Chat Area -->
    <main class="flex-1 flex flex-col bg-gray-900">
        @if($activeRoom)
            <livewire:chat.room :room="$activeRoom" wire:key="room-{{ $activeRoom->id }}" />
        @else
            <div class="flex-1 flex items-center justify-center text-gray-500">
                Selecione uma sala ou pessoa para começar.
            </div>
        @endif
    </main>
</div>
