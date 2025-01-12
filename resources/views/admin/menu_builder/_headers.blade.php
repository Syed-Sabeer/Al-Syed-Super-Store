@php
    $settings = App\Models\Frontend::where('data_keys', 'headers.order.content')->first();
@endphp
<form action="{{ route('admin.menu.builder.update.headers.order') }}" method="post" id="headerForm">
    @csrf

    <div class="card b-radius--10">
        <div class="card-body">
            <div class="d-flex gap-3 flex-column" id="headers">
                @foreach ($settings->data_values as $header)
                    @php
                        $route = str_replace('header_', '', $header);
                        $route = 'admin.menu.builder.header.' . $route;
                    @endphp

                    <div class="header-item {{ menuActive($route) }}">
                        <span class="ui-icon bg-transparent text-muted"><i class="fa fa-grip-vertical"></i></span>
                        <div class="d-flex align-items-center gap-3 justify-content-between flex-grow-1">

                            <span class="flex-shrink-0">{{ __(ucwords(keyToTitle($header))) }}</span>

                            <a href="{{ route($route) }}" class="btn btn-sm btn--light flex-shrink-0"><i class="las la-cog me-0"></i></a>

                            <input type="hidden" name="headers[]" value="{{ $header }}">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-end">
                <button class="btn btn--primary d-none mt-3" form="headerForm" id="headerSubmitButton">@lang('Save Changes')</button>
            </div>
        </div>
    </div>
</form>

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#headers').sortable({
                update: (event, ui) => {
                    $('#headerSubmitButton').removeClass('d-none')
                }
            });
            $('#headers').disableSelection();
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .header-item {
            border: 1px solid #ddd;
            padding: 1rem;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            background: #fff;
        }

        .header-item.active {
            background: #f5f5f5;
        }

        .header-item.active span {
            color: #444;
        }

        .header-item.active .ui-icon i {
            color: #9e9e9e;
        }

        .header-item.active a {
            opacity: 0;
            pointer-events: none;
        }
    </style>
@endpush
