 <div class="nav--links">
     @php
         $menus = $headerOne->links ?? [];
     @endphp
     @if ($menus)
         <ul>
             @foreach ($menus as $menu)
                 <li><a href="{{ url($menu->url) }}">{{ __($menu->name) }}</a></li>
             @endforeach
         </ul>
     @endif
 </div>
