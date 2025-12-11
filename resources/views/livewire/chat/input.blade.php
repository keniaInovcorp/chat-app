<div>
    <!-- File preview-->
    @if($attachment)
        <div class="mb-2 p-2 bg-blue-50 rounded-lg border border-blue-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                </svg>
                <span class="text-xs text-blue-900 font-medium">{{ $attachment->getClientOriginalName() }}</span>
            </div>
            <button wire:click="$set('attachment', null)"
                    type="button"
                    class="text-red-500 hover:text-red-700 text-sm font-bold">
                ✕
            </button>
        </div>
    @endif

    <!-- Loading the file -->
    <div wire:loading wire:target="attachment" class="mb-2 p-2 bg-yellow-50 rounded-lg border border-yellow-200 text-xs text-yellow-800">
        Carregando arquivo...
    </div>

    <form wire:submit.prevent="sendMessage" class="flex items-center"
          x-data
          @input-cleared.window="$el.querySelector('input').value = ''">
        <div class="flex items-center w-full bg-white border border-gray-300 rounded-full px-4 py-2 shadow-sm">
            <!-- Clip button -->
            <label for="attachment-{{ $room->id }}"
                   class="cursor-pointer text-gray-500 hover:text-gray-700 transition mr-2"
                   wire:loading.class="opacity-50 cursor-wait"
                   wire:target="attachment">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            </label>
            <input
                id="attachment-{{ $room->id }}"
                type="file"
                wire:model="attachment"
                accept=".jpg,.jpeg,.png,.gif,.webp,.pdf"
                class="hidden"
            >

            <input
                wire:model.defer="body"
                type="text"
                class="flex-1 bg-transparent border-none text-sm text-black focus:outline-none focus:ring-0 placeholder-gray-500"
                placeholder="Escreva uma mensagem..."
            >

            <button
                type="submit"
                class="ml-3 text-sm font-semibold text-black hover:text-gray-700 focus:outline-none"
            >
                Enviar
            </button>
        </div>
    </form>

    @error('body')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
    @error('attachment')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
