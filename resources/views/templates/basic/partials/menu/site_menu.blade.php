<ul class="menu {{ @$classes }}">
    @foreach ($siteMenu as $menu)
        <li><a href="{{ url($menu->url) }}" @class([
            'active' => url($menu->url) == request()->url() && $menu->url != '/',
        ])>{{ __($menu->name) }}</a></li>
    @endforeach
</ul>
