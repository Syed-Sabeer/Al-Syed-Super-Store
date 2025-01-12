@php
    $headerOne = App\Models\Frontend::where('data_keys', 'header_one.content')->first()?->data_values;
@endphp
@if ($headerOne->status == 'on')

    <div class="header-top d-none d-md-block bg-white">
        <div class="container">
            <div class="header-top-wrap d-flex flex-wrap justify-content-between align-items-center">
                @if (@$headerOne->links_position == 'left')
                    @include('Template::partials.header_top_links')
                    @include('Template::partials.header_top_predefined_options')
                @else
                    @include('Template::partials.header_top_predefined_options')
                    @include('Template::partials.header_top_links')
                @endif
            </div>
        </div>
    </div>
@endif
