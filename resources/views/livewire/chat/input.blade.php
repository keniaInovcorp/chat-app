<form wire:submit.prevent="sendMessage" class="flex items-center" 
      x-data 
      @input-cleared.window="$el.querySelector('input').value = ''">
    <div
        class="flex items-center w-full bg-white border border-gray-300 rounded-full px-4 py-2
               shadow-sm"
    >
        <input
            wire:model.defer="body"
            type="text"
            class="flex-1 bg-transparent border-none text-sm text-black
                   focus:outline-none focus:ring-0 placeholder-gray-500"
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
