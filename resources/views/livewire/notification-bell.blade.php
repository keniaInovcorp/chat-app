<div wire:poll.3s>
    <!-- Bell Icon Button -->
    <button
        wire:click="goToLatestNotification"
        @if($unreadCount === 0) disabled @endif
        class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed"
        type="button"
        title="{{ $unreadCount > 0 ? 'Ir para a última conversa não lida' : 'Sem notificações' }}"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Badge with unread count -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>
</div>
