@extends('Template::layouts.user')
@section('panel')
    @if ($notifications->count())
        <div class="notification-card">
            <div class="notification-header">
                <h6 class="mb-3">{{ __($pageTitle) }}</h6>
            </div>
            <div class="notifications-body">
                <ul class="list-group list-group-flush">
                    @foreach ($notifications as $notification)
                        <li class="notification-item d-flex gap-1 justify-content-between align-items-center @if ($notification->is_read) notification-read @else bg--light @endif">
                            <a href="{{ route('user.notification.read', encrypt($notification->id)) }}" class="link-wrapper"></a>
                            <div class="notification-item__left d-flex">
                                <div class="notification-item__icon">
                                    <i class="far fa-bell"></i>
                                </div>
                                <div class="notification-item__content">
                                    <p class="notification-title mb-0">
                                        {{ $notifications->firstItem() + $loop->index }}.
                                        {{ __($notification->title) }}
                                    </p>
                                    <small class="d-block">{{ showDateTime($notification->created_at, 'd M,Y H:iA') }}</small>
                                </div>
                            </div>
                            <div class="notification-item__right">
                                <span class="notification-link"><i class="las la-angle-right"></i></span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if ($notifications->hasPages())
            <div class="mt-4">
                {{ paginateLinks($notifications) }}
            </div>
        @endif
    @else
        <div class="card custom--card">
            <div class="card-body">
                <x-dynamic-component :component="frontendComponent('empty-message')" :message="$emptyMessage" :isTable="true" />
            </div>
        </div>
    @endif
@endsection

@push('style')
    <style>
        .empty-message img {
            width: auto;
        }

        .notification-item {
            position: relative;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid hsl(var(--border));
            margin-bottom: 10px;
        }

        .link-wrapper {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0px;
            top: 0px
        }

        .notification-item:last-child {
            margin-block: 0px;
        }

        .notification-item__icon {
            display: flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border: 1px solid hsl(var(--border) / 0.5);
            border-radius: 8px;
            flex-shrink: 0;
            background-color: hsl(var(--white))
        }

        .notification-link {
            width: 20px;
            height: 20px;
            color: hsl(var(--body-color));
            font-size: 1.125rem;
        }

        .notification-title {
            color: hsl(var(--body-color) / 0.9);
            font-weight: 600;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            display: -webkit-box;
        }

        .notification-item__left {
            gap: 15px;
        }


        @media (max-width: 575px) {
            .notification-title {
                font-size: 0.875rem;
            }

            .notification-item__left {
                gap: 12px !important;
            }
        }
    </style>
@endpush
