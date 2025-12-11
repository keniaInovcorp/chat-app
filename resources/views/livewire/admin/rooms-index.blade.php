<div class="max-w-5xl mx-auto py-8 space-y-8">
    <h1 class="text-2xl font-bold mb-4">Admin · Salas</h1>

    @if (session()->has('status'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if($editingRoomId)
        <!-- Editing members mode -->
        <div class="bg-yellow-50 border-2 border-yellow-400 rounded p-4 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-yellow-900">
                    Editando membros da sala: {{ $name }}
                </h2>
                <button wire:click="$set('editingRoomId', null)"
                        class="text-sm text-gray-600 hover:text-gray-800 underline">
                    Cancelar
                </button>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selecione os utilizadores</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2 bg-white">
                    @foreach($users as $user)
                        <label class="flex items-center space-x-2 text-sm">
                            <input type="checkbox" value="{{ $user->id }}"
                                   wire:model="selectedUsers"
                                   class="border-gray-300 rounded">
                            <span>{{ $user->name }} ({{ $user->role }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button wire:click="saveMembership"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                 Guardar membros
            </button>
        </div>
    @else
        <!-- Create room form -->
        <form wire:submit.prevent="createRoom" class="bg-white shadow rounded p-4 space-y-4">
            <h2 class="text-lg font-semibold mb-2">Criar nova sala</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nome da sala</label>
                <input type="text" wire:model="name"
                       class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Utilizadores na sala</label>
                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded p-2">
                    @foreach($users as $user)
                        <label class="flex items-center space-x-2 text-sm">
                            <input type="checkbox" value="{{ $user->id }}"
                                   wire:model="selectedUsers"
                                   class="border-gray-300 rounded">
                            <span>{{ $user->name }} ({{ $user->role }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                 Criar sala
            </button>
        </form>
    @endif

    <!-- Rooms list -->
    <div class="bg-white shadow rounded p-4">
        <h2 class="text-lg font-semibold mb-4">Salas existentes</h2>

        <table class="w-full text-sm">
            <thead class="text-left text-gray-500">
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Utilizadores</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($rooms as $room)
                    <tr>
                        <td class="py-2">{{ $room->name ?? 'DM' }}</td>
                        <td class="py-2">{{ $room->is_private ? 'Privada' : 'Pública' }}</td>
                        <td class="py-2">{{ $room->users_count }}</td>
                        <td class="py-2 text-right">
                            @if(!$room->is_private)
                                <button wire:click="editRoom({{ $room->id }})"
                                        class="text-xs text-indigo-600 hover:underline">
                                    Editar membros
                                </button>
                            @else
                                <span class="text-xs text-gray-400">
                                    DM privado
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $rooms->links('vendor.pagination.tailwind') }}
        </div>

    </div>
</div>
