 @php
     $headers = App\Models\Frontend::where('data_keys', 'headers.order.content')->first()->data_values;
     $headerThree = App\Models\Frontend::where('data_keys', 'header_three.content')->first()?->data_values;
     $siteMenu = $headerThree->group->links;
 @endphp


 <div class="header-area bg-white">
     @foreach ($headers as $header)
     @include('Template::partials.headers.'.$header)
     @endforeach
 </div>

 @include('Template::partials.mobile_menu')
