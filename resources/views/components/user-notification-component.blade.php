<div class="dropdown notification-dropdown">
    <button type="button" class="primary--layer ecommerce" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" id="noti-button">
        <span class="ecommerce__icon">
            <i class="las la-bell @if ($unread) icon-left-right @endif"></i>
            @if ($unread)
                <span class="ecommerce__is">{{ $unread > 9 ? '9+' : $unread }}</span>
            @endif
        </span>
        <span class="ecommerce__text">@lang('Notification')</span>
    </button>

    <div class="dropdown-menu dropdown-menu--md dropdown-menu-right">

        @if($unread)
        <div class="notification-inner-header">
            <span class="caption">@lang('Notifications')</span>
            <p>
                @lang('You have') <span class="fw-bold">{{ $unread }}</span> @lang('unread') {{ __(Str::plural('notification', $unread)) }}
            </p>
        </div>
        @endif
        <div class="notification-inner_body">
            @forelse ($notifications as $item)
                <a href="{{ route('user.notification.read', encrypt($item->id)) }}" class="dropdown-menu__item">
                    <div class="navbar-notification">
                        <div class="navbar-notification__right">
                            <h6 class="notifi__title mb-0">{{ __($item->title) }}</h6>
                            <span class="time"><i class="far fa-clock"></i> {{ diffForHumans($item->created_at) }}</span>
                        </div>
                    </div><!-- navbar-notification end -->
                </a>
            @empty

                @php
                    $message = "You have $unread  unread " .Str::plural('notification', $unread);
                @endphp
                <x-dynamic-component :component="frontendComponent('empty-message')" :isTable="true" :message="$message" />

            @endforelse
        </div>
        @if ($unread)
            <div class="notification-inner__footer">
                <a href="{{ route('user.notifications') }}" class="view-all-message">@lang('View all notification')</a>
            </div>
        @endif
    </div>
</div>
