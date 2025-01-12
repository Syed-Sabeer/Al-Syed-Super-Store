@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="py-60">
        <div class="container">
            <div class="section-header left-style">
                <h5 class="title">{{ __($pageTitle) }}</h5>
            </div>

            @if ($offers->count())
                <div class="row gy-4">
                    @foreach ($offers as $offer)
                        @php
                            $startTimestamp = $offer->starts_from->timestamp * 1000;
                            $endTimestamp = $offer->ends_at->timestamp * 1000;
                        @endphp

                        <div class="flash-sell-section col-sm-6 col-xxl-3" data-starts-at="{{ $startTimestamp }}" data-ends-at="{{ $endTimestamp }}">
                            <div class="offer-banner h-100">
                                @if ($offer->show_countdown)
                                    <div class="offer-countdown">
                                        <x-dynamic-component :component="frontendComponent('offer-countdown')" />
                                    </div>
                                @endif

                                <a href="{{ route('offer.products', encrypt($offer->id)) }}" class=" h-100 d-flex justify-content-center  align-items-center">
                                    @if ($offer->banner)
                                        <img class="w-100 rounded--5 h-100" src="{{ getImage(getFilePath('offerBanner') . '/' . $offer->banner) }}" alt="offer-banner">
                                    @else
                                        <h3 class="mt-60">{{ __($offer->name) }}</h3>
                                    @endif
                                </a>

                            </div>
                        </div>
                    @endforeach

                </div>
            @else
                <x-dynamic-component :component="frontendComponent('empty-message')" message="No offer available" />
            @endif
        </div>
    </div>
@endsection


@push('style')
    <style>
        .offer-banner {
            width: unset;
            border: 1px solid hsl(var(--border));
            min-height: 300px;
        }

        .remaining-time__content {
            gap: 24px !important;
        }

        @media (max-width: 991px) {
            .remaining-time .box .box-style {
                width: 35px;
                height: 35px;
            }
        }
    </style>
@endpush
