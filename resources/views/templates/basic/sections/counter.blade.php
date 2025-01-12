@php
    $elements = getContent('counter.element');
@endphp

@if ($elements->count())
    <div class="section--sm counter-section my-60">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="counter-section__container">
                        <ul class="list counter-list">
                            @foreach ($elements as $item)
                                <li>
                                    <div class="counter-list__card">
                                        <div class="counter-list__card-head">
                                            @if (@$item->data_values?->image)
                                                <img src="{{ getImage('assets/images/frontend/counter/' . @$item->data_values->image) }}" alt="@lang('image')" class="counter-list__card-img">
                                            @endif
                                            @if (@$item->data_values->counter_value)
                                                <span class="counter-list__card-title">
                                                    <span class="odometer" data-odometer-final="{{ (int) $item->data_values->counter_value }}">0</span> +
                                                </span>
                                            @endif
                                        </div>

                                        @if (@$item->data_values->title)
                                            <span class="counter-list__card-body">
                                                {{ __($item->data_values->title) }}
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('style-lib')
        <link href="{{ asset($activeTemplateTrue . 'css/odometer-theme-default.css') }}" rel="stylesheet">
    @endpush

    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/viewport.js') }}"></script>
        <script src="{{ asset($activeTemplateTrue . 'js/odometer.js') }}"></script>
    @endpush
@endif
