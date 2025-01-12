@props([
    'message' => null,
    'isTable' => false,
    'showMessage' => true,
])

@if ($isTable)
    <div class="ptc-card-empty">
        <div class="empty-thumb">
            <img src="{{ asset('assets/images/empty_list.png') }}" alt="image">
        </div>
        @if ($showMessage)
            <p class="text-center text-muted">{{ __($message ?? $emptyMessage) }}</p>
        @endif
    </div>
@else
    <div class="empty-message text-center">
        <img src="{{ getImage('assets/images/empty.png') }}" class="lazyload" alt="">
        @if ($showMessage)
            <h6 class="message mt-2">{{ __($message ?? $emptyMessage) }}</h6>
        @endif
    </div>
@endif
