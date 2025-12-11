<div class="max-w-4xl mx-auto py-8 space-y-8">
    <h1 class="text-2xl font-bold mb-4">Admin · Utilizadores</h1>

    <!-- New user form -->
    <form wire:submit.prevent="createUser" class="bg-white shadow rounded p-4 space-y-4">
        @if (session('status'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mb-3 text-sm text-green-700 bg-green-100 border border-green-200 rounded px-3 py-2">
                {{ session('status') }}
            </div>
        @endif
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" wire:model="name"
                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" wire:model="email"
                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
            @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Permissão</label>
            <select wire:model="role"
                    class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Criar utilizador
        </button>
    </form>

    <!-- Users list -->
    <div class="bg-white shadow rounded p-4">
        <h2 class="text-lg font-semibold mb-4">Todos os utilizadores</h2>

        <table class="w-full text-sm">
            <thead class="text-left text-gray-500">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Permissão</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $user)
                    <tr>
                        <td class="py-2">{{ $user->name }}</td>
                        <td class="py-2">{{ $user->email }}</td>
                        <td class="py-2">{{ $user->role }}</td>
                        <td class="py-2">{{ $user->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
