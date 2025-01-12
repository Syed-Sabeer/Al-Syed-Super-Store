@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-3 order-1">
            @include('admin.product.partials.setup_menu')
        </div>
        <div class="col-xxl-7 col-xl-6 col-md-8 col-sm-9 order-2">
            <form action="{{ route('admin.products.specifications.store', $product->id) }}" method="post" id="specificationForm">
                @csrf
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="select2-parent d-flex flex-wrap">
                            <label class="required">@lang('Specification Template')</label>
                            <select class="form-control w-auto flex-grow-1" name="product_type_id">
                                <option  value="">@lang('Select One')</option>
                                @foreach ($specificationTemplates as $template)
                                    <option value="{{ @$template->id }}" data-specifications='@json($template->specifications)' @selected($template->id == @$product->product_type_id)>{{ __($template->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="specifications-wrapper row gy-4"></div>
                <div class="card sticky-submit-button">
                    <div class="card-body">
                        <button type="submit" class="btn btn--lg btn--primary h-45 w-100">
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

        <div class="col-xxl-3 col-xl-3 col-md-12 col-sm-12 order-0 order-xl-3">
            @include('admin.product.partials.product_status', ['formId' => 'specificationForm'])
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            const specificationValues = @json(@$product->specification ?? []);
            const specificationsWrapper = $('.specifications-wrapper');
            const specificationTemplate = $('[name="product_type_id"]');

            const buildSpecificationGroup = (groupName) => {
                return `<div class="col-12">
                        <div class="card">
                            <div class="card-header"><h5 class="card-title">${groupName}</h5></div>
                            <div class="card-body row gy-4"></div>
                        </div>
                    </div>`;
            }

            const buildSpecificationHTML = (specification, count, value = '') => {
                return `<div class="col-sm-6 col-xxl-4">
                            <input type="hidden" name="specification[${count}][key]" value="${specification}" >
                            <label class="color--small">${specification}</label>
                            <input type="text" class="form-control" name="specification[${count}][value]" value="${value}">
                        </div>`;
            }

            const handleProductTypeChange = function() {
                let template = $(this).find(':selected');
                let specifications = template.data('specifications');
                specificationsWrapper.empty();
                if (specifications) {
                    let index = 0;
                    specifications.forEach((specification, i) => {
                        const specificationGroup = buildSpecificationGroup(specification.group_name);
                        const groupElement = appendAndShowElement($(specificationsWrapper), specificationGroup, false);

                        specification.attributes.forEach((attribute => {
                            let value = '';
                            if (specificationValues.length > 0) {
                                const specificationItem = specificationValues.find(item => item.key === attribute);
                                value = specificationItem && specificationItem.value ? specificationItem.value : '';
                            }
                            const content = buildSpecificationHTML(attribute, index, value);
                            appendAndShowElement($(groupElement).find('.card-body'), content, false);
                            index++;
                        }));
                    });
                }
            }

            specificationTemplate.select2({
                dropdownParent: specificationTemplate.parent('.select2-parent'),
            });

            specificationTemplate.on('change', handleProductTypeChange).change();

        })(jQuery);
    </script>
@endpush
