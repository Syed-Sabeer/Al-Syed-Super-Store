@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="compare-section py-60">
        <div class="container">
            <div class="compare-section-wrapper">
                <div class="compare-section-content">
                    @if ($compareProducts->count())
                        <table class="table comparison-table count-{{ $compareProducts->count() + 1 }}">
                            <tbody>
                                <tr class="comparison-header">
                                    <td class="compare-blurb name">
                                        <div class="compare-top-head">
                                            <h4 class="page-heading">{{ __($pageTitle) }}</h4>
                                            {{ $compareProducts->count() }} {{ Str::plural('product', $compareProducts->count()) }} @lang('selected')
                                            <form action="{{ route('compare.remove') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-danger fw-500">@lang('Remove All')</button>
                                            </form>
                                        </div>
                                    </td>
                                    @foreach ($compareProducts as $compareProduct)
                                        <td class="value">
                                            <div class="compare-item-wrapper">
                                                <form action="{{ route('compare.remove', $compareProduct->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="compare-item-btn"> <i class="las la-trash"></i> </button>
                                                </form>
                                                <div class="compare-product-img">
                                                    <img src="{{ $compareProduct->mainImage(false) }}" alt="img">
                                                </div>
                                                <a class="compare-product-name" href="{{ $compareProduct->link() }}">
                                                    <span>{{ __($compareProduct->name) }}</span></a>
                                                <h5 class="compare-product-price">
                                                    @php echo $compareProduct->formattedPrice() @endphp
                                                </h5>

                                                @if (gs('product_review'))
                                                    <div class="ratings-area">
                                                        <span class="ratings">
                                                            @php echo displayRating($compareProduct->avg_rating) @endphp
                                                    </div>
                                                    <p class="rating-text mb-0">@lang("Based on $compareProduct->total_review reviews.")</p>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>

                            </tbody>
                        </table>

                        @foreach ($specificationTemplate->specifications ?? [] as $groupSpecification)
                            <table class="table comparison-table count-{{ $compareProducts->count() + 1 }} border-top-none">
                                <thead class="sliding-header">
                                    <tr>
                                        <td colspan="2">
                                            <i class="material-icons las la-angle-down"></i>
                                            <strong>{{ __($groupSpecification['group_name']) }}</strong>
                                        </td>
                                        @for ($i = 0; $i < $compareProducts->count(); $i++)
                                            <td></td>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody class="sliding-body">
                                    @foreach ($groupSpecification['attributes'] ?? [] as $singleAttribute)
                                        <tr>
                                            <td class="name">{{ $singleAttribute }}</td>
                                            @foreach ($compareProducts as $compareProduct)
                                                <td class="value">
                                                    {{ collect($compareProduct->specification)->where('key', $singleAttribute)?->first()->value ?? '---' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        <div class="empty-message text-center">
                            <div class="empty-thumb">
                                <img src="{{ asset('assets/images/empty_list.png') }}" alt="image">
                            </div>
                            <h5 class="mt-3 text-muted">@lang('No Product Selected')</h5>
                            <p class="message">
                                @lang('Please select at least two products to begin the comparison.')
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            'use strict';
            $('.sliding-header').on('click', function() {
                $(this).parents('.comparison-table').find('.sliding-body').toggle();
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .empty-message img {
            max-width: 100px;
        }
    </style>
@endpush
