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

                <ul class="space-y-1 mb-3 max-h-64 overflow-y-auto">
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
                
                <!-- Search Input -->
                <input
                    type="text"
                    wire:model.live="userSearch"
                    placeholder="Pesquisar..."
                    class="w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg mb-3
                           focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-black placeholder-gray-400"
                >
                
                <!-- Users Grid (3x2) -->
                <div class="grid grid-cols-3 gap-3">
                    @foreach($users as $user)
                        <div
                            wire:click="startDm({{ $user->id }})"
                            class="flex flex-col items-center cursor-pointer group"
                        >
                            <div class="relative">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                         class="w-14 h-14 rounded-full object-cover border-2 border-gray-300 group-hover:border-blue-500 transition">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg border-2 border-gray-300 group-hover:border-blue-500 transition">
                                        {{ $user->initials }}
                                    </div>
                                @endif
                                
                                <!-- Status indicator -->
                                <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full border-2 border-gray-100
                                    {{ $user->status === 'online' ? 'bg-green-500' : 'bg-gray-400' }}">
                                </span>
                            </div>
                            <span class="text-xs text-black font-medium mt-1 text-center truncate w-full">
                                {{ $user->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </aside>

    <!-- Chat Area -->
    <main class="flex-1 flex flex-col bg-gray-100">
        @if($activeRoom)
            <livewire:chat.room :room="$activeRoom" wire:key="room-{{ $activeRoom->id }}" />
        @else
            <div class="flex-1 flex items-center justify-center text-gray-700 font-medium">
                Selecione uma sala ou pessoa para começar.
            </div>
        @endif
    </main>
</div>
