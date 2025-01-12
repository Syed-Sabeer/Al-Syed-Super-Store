<div class="card radius--10px">
    <div class="card-header">
        <h6 class="card-title mb-0">@lang('General')</h6>
    </div>
    <div class="card-body">

        <div class="form-group row">
            <div class="col-md-3 col-sm-4">
                <label>@lang('Name')</label>
            </div>
            <div class="col-md-9">
                <input type="text" class="form-control name-field" value="{{ old('name', @$product->name) }}" name="name">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3 col-sm-4">
                <label>@lang('Slug')</label>
            </div>
            <div class="col-md-9">
                <input type="text" name="slug" class="form-control slug-field" value="{{ old('slug', @$product->slug) }}">
                <span class="text--small text-muted cursor-pointer italic" id="makeSlugBtn">@lang('Use Product Name in Slug')</span>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3 col-sm-4">
                <label>@lang('Type')</label>
            </div>
            <div class="col-md-9">
                <select name="product_type" class="select2 form-control product_type-field" data-minimum-results-for-search="-1">
                    <option value="{{ Status::PRODUCT_TYPE_SIMPLE }}" @selected(@$product->product_type == Status::PRODUCT_TYPE_SIMPLE)>
                        @lang('Simple Product')
                    </option>

                    <option value="{{ Status::PRODUCT_TYPE_VARIABLE }}" @selected(@$product->product_type == Status::PRODUCT_TYPE_VARIABLE)>
                        @lang('Variable Product')
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3 col-sm-4">
                <label>@lang('Brand')</label>
            </div>

            <div class="col-md-9 select2-parent">
                <select class="form-control select2 brand_id-field" name="brand_id">
                    <option value="">@lang('Select One')</option>
                    @foreach ($brands as $brand)
                        <option value="{{ @$brand->id }}" @selected($brand->id == @$product->brand_id)>{{ __($brand->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>




        <div class="form-group row">
            <div class="col-md-3 col-sm-4">
                <label>@lang('Attributes')</label>
            </div>

            <div class="col-md-9 select2-parent">
                <select class="form-control product_attributes-field" name="product_attributes[]" multiple @required(@$product->has_variant == Status::YES)>
                    @foreach ($attributes as $attribute)
                        <option data-type="{{ $attribute->typeInText() }}" data-values="{{ $attribute->attributeValues }}" value="{{ @$attribute->id }}">{{ __($attribute->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="attributesValueWrapper">
        </div>





        <div id="pricingCard">

            <div class="form-group row">
                <div class="col-md-3 col-sm-4">
                    <label>@lang('Regular Price')</label>
                </div>

                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text">{{ gs('cur_sym') }}</span>
                        <input type="number" step="any" class="form-control regular_price-field" name="regular_price" value="{{ old('regular_price', @$product->regular_price) }}">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 col-sm-4">
                    <label>@lang('Sale Price')</label>
                </div>

                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text">{{ gs('cur_sym') }}</span>
                        <input type="number" step="any" class="form-control sale_price-field" name="sale_price" value="{{ old('sale_price', @$product->sale_price) }}">
                    </div>

                    <div class="mt-1">
                        <a class="text-muted text-underline" data-bs-toggle="collapse" href="#schedule" role="button" aria-expanded="false" aria-controls="schedule">
                            @lang('Schedule')
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse @if ($product && ($product->sale_starts_from || $product->sale_ends_at)) show @endif" id="schedule">
            <div class="form-group row">
                <div class="col-md-3 col-sm-4">
                    <label for="sale_starts_from">@lang('Sale Starts From')</label>
                </div>

                @php
                    $startDate = @$product->sale_starts_from ? showDateTime(@$product->sale_starts_from, 'Y-m-d h:i A') : null;
                @endphp

                <div class="col-md-9">
                    <input type="text" name="sale_starts_from" class="form-control sale_starts_from-field" value="{{ old('sale_starts_from', $startDate) }}" autocomplete="off">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 col-sm-4">
                    <label for="sale_ends_at">@lang('Sale Ends At')</label>
                </div>

                @php $endDate = @$product->sale_ends_at ? showDateTime(@$product->sale_ends_at, 'Y-m-d h:i A') : null;@endphp

                <div class="col-md-9">
                    <input type="text" name="sale_ends_at" class="form-control sale_ends_at-field" value="{{ old('sale_ends_at', $endDate) }}" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            'use strict';
            const makeSlugButton = $('#makeSlugBtn');

            const slugField = $('[name=slug]');
            const startsFromField = $('[name=sale_starts_from]');
            const endsAtField = $('[name=sale_ends_at]');

            const dateRangeOptions = {
                autoUpdateInput: false,
                timePicker: true,
                singleDatePicker: true,
                drops: 'up',
                locale: {
                    format: 'YYYY-MM-DD hh:mm A'
                }
            };

            function initDateTimePicker(element) {
                element.daterangepicker(dateRangeOptions);
            }

            initDateTimePicker(startsFromField);
            initDateTimePicker(endsAtField);

            const changeDateTime = (element, dateTime) => {
                $(element).val(dateTime.format('YYYY-MM-DD hh:mm A'));
            }

            const setSlugField = (value) => slugField.val(createSlug(value));
            const handleMakeSlugBtnClick = () => setSlugField($('[name=name]').val());

            slugField.on('focusout', () => setSlugField(slugField.val()));
            makeSlugButton.on('click', handleMakeSlugBtnClick);

            startsFromField.on('apply.daterangepicker', (event, picker) => changeDateTime(event.target, picker.startDate));
            endsAtField.on('apply.daterangepicker', (event, picker) => changeDateTime(event.target, picker.startDate));

        })(jQuery);
    </script>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush
