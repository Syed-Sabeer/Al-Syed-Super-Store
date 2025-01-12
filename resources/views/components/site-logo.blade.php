@props([
    'type' => '',
])
<div class="logo">
    <a href="{{ route('home') }}">
        <img src="{{ siteLogo($type) }}" alt="@lang('logo')">
    </a>
</div>
