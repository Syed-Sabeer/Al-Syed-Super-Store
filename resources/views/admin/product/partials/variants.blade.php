@php
    $variants = $product->productVariants;
@endphp

@if (blank($variants))
    @include('admin.product.partials.generate_variants')
@else
    <div class="row gy-4">

        @php
            $attributeForImage = $product->attributes->whereIn('type', [Status::ATTRIBUTE_TYPE_COLOR, Status::ATTRIBUTE_TYPE_IMAGE])->first();
        @endphp

        <div class="col-lg-12">
            <div class="d-flex justify-content-end">
                <div class="form-group d-flex justify-content-between align-items-center gap-2">
                    <label class="text--small">@lang('Publish All')</label>
                    <x-toggle-switch id="publishAll" />
                </div>
            </div>

            <div class="row gy-3 position-sticky">

                @foreach ($variants as $variant)
                    @php
                        $startDate = $variant->sale_starts_from ? showDateTime($variant->sale_starts_from, 'Y-m-d h:i A') : null;
                        $endDate = $variant->sale_ends_at ? showDateTime(@$variant->sale_ends_at, 'Y-m-d h:i A') : null;
                    @endphp

                    <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header variant-item d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h6 class="card-title mb-0">{{ $variant->name }}</h6>
                                <div class="d-flex align-items-center gap-3 variant-item-bottom">
                                    <div class="d-flex justify-content-between align-items-center gap-2">
                                        <label class="mb-0">@lang('Published')</label>
                                        <x-toggle-switch name="variants[{{ $loop->index }}][is_published]" value="1" :checked="@$variant->is_published" class="publishVariant" />
                                    </div>

                                    <a class="text-muted text-decoration-underline expandVariantBtn" data-bs-toggle="collapse" href="#singleVariant{{ $loop->index }}" role="button" aria-expanded="false" aria-controls="singleVariant{{ $loop->index }}">
                                        @lang('Expand')
                                    </a>
                                </div>
                            </div>

                            <div class="card-body collapse singleVariantItem" id="singleVariant{{ $loop->index }}">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="row gx-5">
                                            <div class="col-xl-12">
                                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mb-2">
                                                    <div class="d-flex flex-wrap gap-4">
                                                        <div class="d-flex gap-2 flex-shrink-0">
                                                            <x-toggle-switch name="variants[{{ $loop->index }}][manage_stock]" value="1" :checked="@$variant->manage_stock" class="manageStock" />
                                                            <label>@lang('Manage Stock')</label>
                                                        </div>

                                                        <div class="d-flex gap-2 flex-shrink-0 trackInventoryWrapper">
                                                            <x-toggle-switch name="variants[{{ $loop->index }}][track_inventory]" value="1" :checked="@$variant->track_inventory" class="trackInventory" />
                                                            <label>@lang('Track Inventory')</label>
                                                        </div>

                                                        <div class="d-flex gap-2 flex-shrink-0 showStockWrapper">
                                                            <x-toggle-switch name="variants[{{ $loop->index }}][show_stock]" value="1" :checked="@$variant->show_stock" />
                                                            <label>@lang('Show Stock')</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row gy-4">
                                                    <div class="col-lg-6">
                                                        <div class="form-group stockQuantityWrapper">
                                                            <label>@lang('In Stock')</label>
                                                            <input type="number" name="variants[{{ $loop->index }}][in_stock]" class="form-control" value="{{ $variant->in_stock }}" />
                                                            @if (@$variant->track_inventory)
                                                                <a href="{{ route('admin.products.stock.log.variant', $variant->id) }}" class="text-muted text-decoration-underline">@lang('View Stock Log')</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group alertQuantityWrapper">
                                                            <label>@lang('Alert Quantity')</label>
                                                            <input type="number" name="variants[{{ $loop->index }}][alert_quantity]" class="form-control" value="{{ $variant->alert_quantity }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label class="text-small">@lang('SKU')</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="variants[{{ $loop->index }}][sku]" value="{{ $variant->sku }}">
                                                        <button type="button" class="input-group-text text-muted autoGeneratedSku" data-sku="{{ generateSKU($product, $variant->name) }}">
                                                            @lang('Generate')
                                                        </button>
                                                    </div>
                                                </div>

                                                @if ($product->is_downloadable && $product->delivery_type == Status::DOWNLOAD_INSTANT)
                                                    <div class="form-group">
                                                        <label>@lang('File')</label>
                                                        <input type="file" class="form-control" name="variants[{{ $loop->index }}][file]" accept=".zip" />
                                                        @if (@$variant->digitalFile)
                                                            <div class="text-end">
                                                                <a href="{{ route('admin.products.digital.download', encrypt($variant->digitalFile->id)) }}">@lang('View File')</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <h5 class="border-bottom pb-2 mb-3">@lang('Pricing')</h5>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label>@lang('Regular Price')</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">@lang(gs('cur_sym'))</span>
                                                                <input type="number" step="any" class="form-control" name="variants[{{ $loop->index }}][regular_price]" value="{{ $variant->regular_price }}" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="salePrice{{ $loop->index }}">@lang('Sale Price')</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">@lang(gs('cur_sym'))</span>
                                                                <input type="number" class="form-control" step="any" id="salePrice{{ $loop->index }}" name="variants[{{ $loop->index }}][sale_price]" value="{{ @$variant->sale_price }}" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label>@lang('Sale Starts From')</label>
                                                            <input type="text" name="variants[{{ $loop->index }}][sale_starts_from]" class="form-control dateTime" value="{{ $startDate }}" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="sale_ends_at">@lang('Sale Ends At')</label>
                                                            <input type="text" name="variants[{{ $loop->index }}][sale_ends_at]" class="form-control dateTime" value="{{ old('sale_ends_at', $endDate) }}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">

                                        <div class="d-flex gap-3 variant-item-wrapper">
                                            <div>
                                                <label>@lang('Main Images')</label>
                                                @php
                                                    $mainImage = $variant ? $variant->displayImage : null;
                                                @endphp

                                                <x-media-uploader-input id="mainImage-{{ $loop->index }}" for="product" isSingle="1" :images="$mainImage" inputName="variants[{{ $loop->index }}][main_image]" />

                                                <span class="text-muted text--small">@lang('Image Size') <b>{{ getFileSize('product') }}</b> @lang('px')</b></small>
                                            </div>

                                            <div class="flex-grow-1">
                                                <label>@lang('Gallery Images')</label>

                                                @php
                                                    $galleryImages = @$variant?->galleryImages ?? collect([]);
                                                @endphp

                                                <x-media-uploader-input id="galleryImages-{{ $loop->index }}" for="product" :images="$galleryImages" inputName="variants[{{ $loop->index }}][gallery_images]" />

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    @push('style-lib')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/css/image-uploader.min.css') }}">
    @endpush

    @push('script-lib')
        <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
    @endpush

    @push('script')
        <script>
            (function($) {
                "use strict";
                const generateSkuBtn = $('.autoGeneratedSku');
                const datepickers = $('.dateTime');
                const manageStockCheckBox = $('.manageStock');
                const trackInventoryCheckBox = $('.trackInventory');
                const publishCheckBox = $('.publishVariant');
                const expandVariantBtn = $('.expandVariantBtn');
                const publishAllCheckBox = $('#publishAll');
                let isSubmitting = false;
                let dateTimeInitiator;

                const generateSkuBtnClickHandler = function() {
                    $(this).siblings('input').val($(this).data('sku'))
                }

                datepickers.each((i, element) => {
                    dateTimeInitiator = $(element).daterangepicker({
                        timePicker: true,
                        singleDatePicker: true,
                        autoUpdateInput: false,
                        locale: {
                            format: 'YYYY-MM-DD hh:mm A'
                        }
                    });

                    if ($(element).val()) {
                        let date = new Date($(element).val());
                        $(element).data('daterangepicker').setStartDate(date);
                        $(element).data('daterangepicker').setEndDate(date);
                    }
                });

                datepickers.on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD hh:mm A'));
                });

                datepickers.on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

                const showOrHideStockFields = (manageStock, trackInventory, variantItem) => {
                    let trackInventoryWrapper = variantItem.find('.trackInventoryWrapper');

                    let showStockWrapper = variantItem.find('.showStockWrapper');
                    let stockQuantityWrapper = variantItem.find('.stockQuantityWrapper');
                    let alertQuantityWrapper = variantItem.find('.alertQuantityWrapper');

                    if (manageStock) {
                        trackInventoryWrapper.removeClass('d-none');
                    } else {
                        trackInventoryWrapper.find('input').prop('checked', false);
                        trackInventoryWrapper.addClass('d-none');
                    }

                    if (trackInventory) {
                        showStockWrapper.removeClass('d-none');
                        stockQuantityWrapper.removeClass('d-none');
                        alertQuantityWrapper.removeClass('d-none');
                    } else {
                        showStockWrapper.find('input').prop('checked', false);
                        stockQuantityWrapper.find('input').val('');
                        alertQuantityWrapper.find('input').val('');
                        showStockWrapper.addClass('d-none');
                        stockQuantityWrapper.addClass('d-none');
                        alertQuantityWrapper.addClass('d-none');
                    }
                }

                const showStockFields = (manageStockSwitch) => {
                    let manageStock = manageStockSwitch.prop('checked');
                    let variantItem = manageStockSwitch.parents('.singleVariantItem');

                    showOrHideStockFields(manageStock, variantItem.find(trackInventoryCheckBox).prop('checked'), variantItem);
                }

                const showInventoryFields = function(trackInventorySwitch) {
                    showOrHideStockFields(true, trackInventorySwitch.prop('checked'), trackInventorySwitch.parents('.singleVariantItem'));
                }

                const expandVariantBtnClickHandler = function() {
                    if ($(this).hasClass('collapsed')) {
                        $(this).text("@lang('Expand')");
                    } else {
                        $(this).text("@lang('Collapse')");
                    }
                }

                const publishCheckBoxClickHandler = function() {
                    togglePublishAllSwitch();
                }

                const publishAllCheckBoxClickHandler = function() {
                    publishCheckBox.prop('checked', $(this).prop('checked'));
                }

                const togglePublishAllSwitch = () => {
                    const total = publishCheckBox.length;
                    const checked = $('.publishVariant:checked').length;
                    publishAllCheckBox.prop('checked', total == checked);
                }

                trackInventoryCheckBox.on('click', function() {
                    showInventoryFields($(this));
                });

                manageStockCheckBox.on('click', function() {
                    showStockFields($(this));
                });


                publishAllCheckBox.on('click', publishAllCheckBoxClickHandler);
                publishCheckBox.on('click', publishCheckBoxClickHandler);
                manageStockCheckBox.each((i, element) => showStockFields($(element)));
                generateSkuBtn.on('click', generateSkuBtnClickHandler);
                expandVariantBtn.on('click', expandVariantBtnClickHandler);

                togglePublishAllSwitch();


            })(jQuery);
        </script>
    @endpush

    @push('style')
        <style>
            .variant-item {
                padding: 12px;
            }

            @media (max-width: 400px) {
                .variant-item-bottom {
                    justify-content: space-between;
                    width: 100%;
                }
            }

            .sticky-submit-btn {
                position: sticky;
                bottom: 0;
                z-index: 1;
                text-align: right;
                margin-top: 10px;
            }

            .image--uploader {
                width: 145px;
            }

            .image-upload-wrapper {
                height: 140px;
            }

            @media (max-width: 430px) {
                .variant-item-wrapper {
                    flex-wrap: wrap !important;
                }
            }
        </style>
    @endpush

@endif
