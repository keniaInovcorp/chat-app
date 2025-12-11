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
                        @if($message->body)
                            <p>{{ $message->body }}</p>
                        @endif

                        @if($message->attachment)
                            @php
                                $extension = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            @if($isImage)
                                <!-- Image preview -->
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $message->attachment) }}"
                                             alt="Attachment"
                                             class="max-w-full rounded-lg border border-gray-300 hover:opacity-90 transition"
                                             style="max-height: 300px;">
                                    </a>
                                </div>
                            @else
                                <!-- Link to download PDF -->
                                <div class="mt-2 flex items-center gap-2 p-2 bg-gray-50 rounded border border-gray-300">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ asset('storage/' . $message->attachment) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline text-sm font-medium">
                                         {{ basename($message->attachment) }}
                                    </a>
                                </div>
                            @endif
                        @endif
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
