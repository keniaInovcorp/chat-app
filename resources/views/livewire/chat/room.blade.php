<div class="flex flex-col h-full bg-gray-900">
    <!-- Header -->
    <div class="h-16 border-b border-gray-800 flex items-center px-6">
        <h2 class="text-lg font-bold text-gray-100">
            {{ $room->is_private ? 'Conversa direta' : '# '.$room->name }}
        </h2>
    </div>

    <!-- Messages List -->
    <div
        x-data="{ scroll() { $el.scrollTop = $el.scrollHeight } }"
        x-init="scroll()"
        @message-sent.window="setTimeout(() => scroll(), 50)"
        class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-900"
    >
        @foreach($messages as $message)
            <div class="flex space-x-3 group">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-sm">
                        {{ strtoupper(mb_substr($message->sender->name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline space-x-2">
                        <span class="font-semibold text-gray-100">
                            {{ $message->sender->name }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                    <div class="text-gray-200 mt-1">
                        {{ $message->body }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input Area -->
    <div class="p-4 border-t border-gray-800 bg-gray-900">
        {{-- <livewire:chat.input :room="$room" /> --}}
    </div>
</div>
