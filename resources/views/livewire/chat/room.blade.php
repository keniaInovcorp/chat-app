<div class="flex flex-col bg-white rounded-lg shadow-lg max-h-[75vh] h-[75vh] overflow-hidden border border-gray-200">
    <!-- Header -->
    <div class="flex-shrink-0 h-16 border-b border-gray-200 flex items-center justify-between px-6 bg-white">
        <h2 class="text-lg font-bold text-gray-900">
            {{ $room->is_private ? 'Conversa direta' : '# '.$room->name }}
        </h2>

        <!-- Room Members Avatars -->
        <div class="flex items-center gap-1 overflow-x-auto max-w-[200px] [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
            @foreach($members as $member)
                <div class="flex-shrink-0" title="{{ $member->name }}">
                    @if($member->avatar_url)
                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}"
                             class="w-8 h-8 rounded-full object-cover border-2 border-gray-300">
                    @else
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-semibold border-2 border-gray-300">
                            {{ $member->initials }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Messages List, takes available space inside the card, scrolls internally -->
    <div
        x-data="{ scroll() { $el.scrollTop = $el.scrollHeight } }"
        x-init="scroll()"
        @message-sent.window="setTimeout(() => scroll(), 50)"
        class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
    >
        @foreach($messages as $message)
            <div class="flex space-x-3 group">
                <div class="flex-shrink-0">
                    @if($message->sender->avatar_url)
                        <img src="{{ $message->sender->avatar_url }}" alt="{{ $message->sender->name }}"
                             class="h-10 w-10 rounded-full object-cover border-2 border-gray-300">
                    @else
                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-sm font-semibold text-white">
                            {{ $message->sender->initials }}
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-baseline space-x-2">
                        <span class="font-semibold text-gray-900">
                            {{ $message->sender->name }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                    <div class="mt-1 inline-block max-w-xl rounded-lg bg-white px-4 py-2 text-sm text-gray-900 shadow-sm border border-gray-200">
                        {{ $message->body }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Input Area fixed at the bottom -->
    <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
        <livewire:chat.input :room="$room" />
    </div>
</div>
