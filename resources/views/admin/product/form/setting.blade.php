@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-3 order-1">
            @include('admin.product.partials.setup_menu')
        </div>

        <div class="col-xxl-10 col-lg-9 col-md-8 col-sm-9 order-2">
            <form action="{{ route('admin.products.store', @$product->id) }}" id="productForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row gy-4">
                    <div class="col-xxl-8 col-xl-8">
                        <!-- Tabs content -->
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                @include('admin.product.partials.general')
                            </div>

                            <div class="tab-pane fade" id="media-content" role="tabpanel" aria-labelledby="media-content-tab">
                                @include('admin.product.partials.media_contents')
                            </div>

                            <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                                @include('admin.product.partials.inventory')
                            </div>

                            @if (@$product && $product->product_type == Status::PRODUCT_TYPE_VARIABLE)
                                <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                                    @include('admin.product.partials.variants')
                                </div>
                            @endif

                            @if (@$product)
                                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                                    @include('admin.product.partials.specifications')
                                </div>
                            @endif

                            <div class="tab-pane fade show" id="description" role="tabpanel" aria-labelledby="description-tab">
                                @include('admin.product.partials.product_description')
                            </div>

                            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                @include('admin.product.partials.seo_content')
                            </div>

                            <div class="tab-pane fade show" id="extra-description" role="tabpanel" aria-labelledby="extra-description-tab">
                                @include('admin.product.partials.extra_description')
                            </div>

                            <div class="tab-pane fade" id="downloadable-config" role="tabpanel" aria-labelledby="downloadable-config-tab">
                                @include('admin.product.partials.downloadable_config')
                            </div>
                        </div>

                        <button type="submit" class="btn btn--primary h-45 w-100 mt-3 submitBtn d-none d-xl-block">
                            @if (@$product->id)
                                @lang('Save Changes')
                            @else
                                @lang('Submit')
                            @endif
                        </button>

                    </div>
                    <div class="col-xxl-4 col-xl-4 order-0">
                        @include('admin.product.partials.product_status')
                        @include('admin.product.partials.product_categories')
                    </div>

                    <div class="col-xxl-8 col-xl-8 d-block d-xl-none">
                        <button type="submit" class="btn btn--primary h-45 w-100 submitBtn">
                            @if (@$product->id)
                                @lang('Save Changes')
                            @else
                                @lang('Submit')
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <x-media-uploader />

    @stack('modal')
@endsection

@if (Route::is('admin.products.edit'))
    @push('breadcrumb-plugins')
        <a href="{{ route('product.detail', $product->slug) }}" target="blank" class="btn btn--primary view-in-shop">@lang('View In Shop')</a>
    @endpush
@endif

@push('style')
    <style>
        .nav-pills-custom .nav-link {
            color: #555555;
            background: #fff;
            position: relative;
            font-size: .875rem;
            font-weight: 500;
            flex-grow: 1;
        }

        .nav-pills-custom .nav-link.active {
            color: #4634ff;
            background: #fff;
        }

        .nav-pills-custom .nav-link.active::before {
            opacity: 1;
        }

        .extra {
            border: 1px solid #ebebeb;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            const form = $('#productForm');
            const productType = $('[name=product_type]');
            const trackInventoryCheckBox = $('[name="track_inventory"]');
            const attributesValueWrapper = $('#attributesValueWrapper');

            const showStockWrapper = $('.show-stock-wrapper');
            const stockQuantityWrapper = $('#stockQuantityWrapper');
            const alertQuantityWrapper = $('#alertQuantityWrapper');
            const attributeSelectElement = $('[name="product_attributes[]"]');

            const deliverTypeField = $('[name=delivery_type]');
            const digitalFileWrapper = $('#digitalFileWrapper');
            const isDigitalField = $('[name=is_downloadable]');
            let nicEditorInstanceForDescription = null;

            let isSubmitting = false;
            let isChangeAttribute = false;

            const productAttributeValues = @json($attributeValues);

            attributeSelectElement.val(@json($productAttributes)).trigger("change");

            const selectedAttribute = attributeSelectElement.find(':selected');


            const handleExtraDescriptionTabShow = () => {
                const textFields = $(`#extra-description`).find('.nicEdit');
                textFields.each((i, element) => {

                    let nicfield = $(element).parent().find('.nicEdit-main');

                    nicfield.css({
                        "width": "100%"
                    })

                    nicfield.parent().css({
                        "width": "100%"
                    });

                    nicfield.parent().prev().css({
                        "width": "100%"
                    });
                });
            }

            const handleDescriptionTabShow = () => {
                const textareaField = 'productDescription';

                if (!nicEditorInstanceForDescription) {
                    nicEditorInstanceForDescription = new nicEditor({
                        fullPanel: true
                    });
                }

                if (nicEditorInstanceForDescription.nicInstances.filter((element) => element.e.id == textareaField).length == 0) {
                    nicEditorInstanceForDescription.panelInstance(textareaField);
                }
            }

            const saveNicContent = (element) => {
                var nicEditorInstance = nicEditors.findEditor(element);
                if (nicEditorInstance) {
                    nicEditorInstance.saveContent(); // Update the textarea with the editor content
                }
            }

            const updateTextareaContent = () => {
                const textareaField = 'productDescription';

                saveNicContent(textareaField);

                let extraDescriptionFields = $(document).find('.extra_description-field');
                if (extraDescriptionFields.length > 0) {
                    $.each(extraDescriptionFields, function(index, element) {
                        saveNicContent(element);
                    });
                }
            }


            const attributeUnselectHandler = (e) => {
                const data = e.params.data;
                $(attributesValueWrapper).find(`#attribute-row-${data.id}`).slideUp('slow', function() {
                    $(this).remove();
                });
            }

            const formatAttributes = (state) => {
                if (!state.id) {
                    return state.text;
                }

                const data = state.element.dataset;

                const attributeStyle = {
                    display: "inline-flex",
                    height: "20px",
                    width: "20px",
                    marginRight: "7px",
                    borderRadius: "50%",
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                    color: "inherit",
                }

                let formattedAttribute;

                if (data.type === 'color') {
                    formattedAttribute = $(`<div><span style="background-color: #${data.value}">&nbsp;</span>${state.text}</div>`);
                } else if (data.type === 'img') {
                    const basePath = `{{ url('') }}`;
                    const imagePath = `{{ getFilePath('attribute') }}`;
                    const image = basePath + '/' + imagePath + "/" + data.value;
                    formattedAttribute = $(`<div class="d-flex align-items-center">
                        <span style="background-image: url(${image})"></span>${state.text}
                    </div>`);
                } else {
                    if (data.value == state.text) {
                        formattedAttribute = $(`<div>${state.text}</div>`);
                    } else {
                        formattedAttribute = $(`<div><span class="w-auto">${data.value}</span>${state.text}</div>`);
                    }
                }

                formattedAttribute.find('span').css(attributeStyle);

                return formattedAttribute;
            }

            const buildAttributeField = (data, animate = true) => {
                const dataset = data.dataset;
                const attributeValues = JSON.parse(dataset.values);

                const content = `<div class="form-group row" id="attribute-row-${data.value}">
                  <div class="col-md-3 col-sm-4">
                        <label>${data.text}</label>
                    </div>

                    <div class="col-md-9 select2-parent">
                        <select class="form-control attribute_values-field" name="attribute_values[${data.value}][]" id="attribute${data.value}" multiple>
                            ${
                                attributeValues.map((item) => {
                                    return `<option value="${item.id}" data-type="${dataset.type}" data-value="${item.value}">${item.name}</option>`;
                                }).join('')
                            }
                        </select>
                    </div>
                </div>`;

                appendAndShowElement(attributesValueWrapper, content, animate);

                if (productAttributeValues[data.value]) {
                    $(attributesValueWrapper).find(`#attribute${data.value}`).val(productAttributeValues[data.value])
                }

                $(attributesValueWrapper).find(`#attribute${data.value}`).select2({
                    dropdownParent: $(attributesValueWrapper).find(`#attribute${data.value}`).parent('.select2-parent'),
                    closeOnSelect: false,
                    templateResult: formatAttributes
                });

            }

            selectedAttribute.each(function(index, element) {
                buildAttributeField(element, false);
            });

            const attributeSelectHandler = function(e) {
                const data = e.params.data;
                buildAttributeField(data.element);
            }

            const handleSubmitForm = (e) => {

                e.preventDefault();

                if (isSubmitting) {
                    return;
                }

                updateTextareaContent();
                isSubmitting = true;

                let btn = form.find('button[type=submit]');
                btn.prop('disabled', true);
                btn.html('<i class="fa fa-circle-notch fa-spin" aria-hidden="true"></i>');
                let formData = new FormData(form[0]);

                $.ajax({
                    url: form.prop('action'),
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: handleFormSubmission
                }).always((response) => {
                    isSubmitting = false;
                    btn.prop('disabled', false);
                    btn.text(`@lang('Submit')`);
                }).done(function() {
                    let slug = $('.slug-field').val();
                    if ($('.view-in-shop').length) {
                        let link = `{{ route('product.detail', ':slug') }}`;
                        $('.view-in-shop').attr('href', link.replace(':slug', slug));
                    }
                });
            }

            const showOrHideAttributesFields = () => {
                const variantCard = $('#variantCard');
                if (checkIsVariableProduct()) {
                    attributeSelectElement.removeAttr('disabled');
                    variantCard.removeClass('d-none');
                    attributesValueWrapper.removeClass('d-none');
                } else {
                    $('.product_attributes-field').val('').change();
                    $('.attribute_values-field').val('').change();
                    attributeSelectElement.attr('disabled', true);
                    variantCard.addClass('d-none');
                    attributesValueWrapper.addClass('d-none');
                }
                attributeSelectElement
            }

            const handleDeliverTypeChange = () => showOrHideDigitalFileField();

            const showOrHideDigitalFileField = () => {
                if (deliverTypeField.val() == 1 && !checkIsVariableProduct()) {
                    digitalFileWrapper.removeClass('d-none');
                } else {
                    digitalFileWrapper.addClass('d-none');
                }
            }

            const showOrHidePricingFields = () => {
                const pricingCard = $('#pricingCard');

                if (checkIsVariableProduct()) {
                    pricingCard.find('input').val('');
                    pricingCard.hide();
                } else {
                    pricingCard.show();
                }
            }

            const handleProductTypeChange = () => {
                showOrHidePricingFields();
                showOrHideAttributesFields();
                showOrHideDigitalFileField();
            }

            const showInventoryFields = () => {
                let trackInventory = $('input[name=track_inventory]').prop('checked');
                let hasVariant = checkIsVariableProduct();

                if (trackInventory) {
                    showStockWrapper.removeClass('d-none');
                    stockQuantityWrapper.removeClass('d-none');
                    alertQuantityWrapper.removeClass('d-none');
                } else {
                    showStockWrapper.find('input').prop('checked', false);
                    showStockWrapper.addClass('d-none');
                    stockQuantityWrapper.find('input').val('');
                    alertQuantityWrapper.find('input').val('');
                    stockQuantityWrapper.addClass('d-none');
                    alertQuantityWrapper.addClass('d-none');
                }
            }

            const handleFormSubmission = (response) => {


                notify(response.status, response.message);
                if ((response.isUpdate == false || isChangeAttribute) && response.redirectTo) {
                    window.location.href = response.redirectTo;
                }else{
                    window.location.reload();
                }


                if (response.status == 'error') {
                    let fields = $(`.${Object.keys(response.message)[0]}-field`);

                    if (fields.length > 0) {
                        $.each(fields, function(index, element) {
                            if (!$(this).val().length) {
                                let tabName = $(element).parents('.tab-pane').attr('aria-labelledby');
                                let tab = $(`#${tabName}`);
                                tab.tab('show');

                                setTimeout(() => {
                                    $(element).focus();
                                }, 200);

                                return false;
                            }
                        });
                    }
                }
            }

            const checkIsVariableProduct = () => productType.val() == @json(Status::PRODUCT_TYPE_VARIABLE);

            attributeSelectElement.select2({
                dropdownParent: attributeSelectElement.parent('.select2-parent'),
                closeOnSelect: false,
            }).on('select2:select', attributeSelectHandler).on('select2:unselect', attributeUnselectHandler);

            const handleIsDigitalFieldClick = () => {
                let isDigital = isDigitalField.prop('checked');

                if (isDigital) {
                    $('#deliverTypeWrapper').removeClass('d-none');
                } else {
                    $('#deliverTypeWrapper').addClass('d-none');
                }
            }


            $('#description-tab').on('shown.bs.tab', () => handleDescriptionTabShow());
            $('#extra-description-tab').on('shown.bs.tab', () => handleExtraDescriptionTabShow());

            form.on('submit', handleSubmitForm);
            isDigitalField.on('click', handleIsDigitalFieldClick);
            deliverTypeField.on('change', handleDeliverTypeChange).change();
            productType.on('change', handleProductTypeChange).change();
            trackInventoryCheckBox.on('click', showInventoryFields);

            showInventoryFields();
            showOrHideAttributesFields();
            handleIsDigitalFieldClick();

            $(document).on('change', ".product_attributes-field, .attribute_values-field", function() {
                isChangeAttribute = true;

            });


            (function() {
                const uri = window.location.href;
                let targetTab = uri.split('#')[1];

                if (targetTab != 'general') {
                    if (targetTab) {
                        const tabId = `#${targetTab}-tab`;
                        const contentId = `#${targetTab}`;
                        if ($(tabId).length && $(contentId).length) {
                            $('.nav-link').removeClass('active');
                            $('.tab-pane').removeClass('show active');

                            // Add 'active' class to the target tab and its content
                            $(tabId).addClass('active');
                            $(contentId).addClass('show active');

                            // Use Bootstrap's tab functionality to show the content
                            const tab = new bootstrap.Tab($(tabId)[0]);
                            tab.show();

                            if (targetTab == 'description') {
                                handleDescriptionTabShow();
                            }

                            if (targetTab == 'extra-description') {
                                handleExtraDescriptionTabShow();
                            }
                        }
                    }
                }
            })();
        })(jQuery);
    </script>
@endpush
