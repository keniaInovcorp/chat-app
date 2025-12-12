<?php

namespace App\Livewire;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Livewire component for displaying notifications bell in the navbar.
 * Shows unread notification count. Clicking goes to the latest unread conversation.
 */
class NotificationBell extends Component
{
    /**
     * Get the count of different rooms with unread notifications for the authenticated user.
     * Returns the number of distinct rooms, not the total number of messages.
     *
     * @return int
     */
    public function getUnreadCountProperty(): int
    {
        return Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->distinct()
            ->count('chat_room_id');
    }

    /**
     * Get the latest unread notification.
     *
     * @return Notification|null
     */
    public function getLatestNotificationProperty()
    {
        return Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->with('room')
            ->latest()
            ->first();
    }

    /**
     * Go to the latest unread conversation.
     * Marks the notification as read and redirects to the chat room.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function goToLatestNotification()
    {
        $notification = $this->latestNotification;

        if (!$notification) {
            return;
        }

        $roomId = $notification->chat_room_id;

        // Mark notification as read
        $notification->markAsRead();

        // Always redirect to chat page with room parameter
        return redirect()->route('chat.index', ['room' => $roomId]);
    }

    /**
     * Render the notification bell component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.notification-bell', [
            'unreadCount' => $this->unreadCount,
        ]);
    }
}
