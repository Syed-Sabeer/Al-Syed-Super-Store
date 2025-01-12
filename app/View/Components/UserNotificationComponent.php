<?php

namespace App\View\Components;

use App\Models\UserNotification;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserNotificationComponent extends Component
{
    /**
     * Create a new component instance.
     */

    public $unread;
    public $notifications;
    public function __construct()
    {
        $userNotifications = UserNotification::where('user_id', auth()->id())->where('is_read', 0);

        $this->unread = (clone $userNotifications)->count();
        $this->notifications = $userNotifications->latest()->limit(4)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-notification-component');
    }
}
