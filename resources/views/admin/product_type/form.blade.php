@extends('admin.layouts.app')

@section('panel')
    <form action="{{ route('admin.product.type.store', $template->id ?? 0) }}" method="post" id="speficationForm">
        @csrf

        <div class="card add-group-btn-wrapper mb-4">
            <div class="card-body justify-content-between align-items-center">
                <div class="d-flex gap-3 flex-wrap  mb-3">
                    <label class="required d-bloc  me-3">@lang('Product Type')</label>
                    <input type="text" class="form-control w-auto flex-grow-1" name="name" value="{{ old('name', @$template->name) }}" required>
                </div>

                <button type="button" class="btn btn--dark" id="addGroupBtn">
                    <i class="la la-plus"></i> @lang('Specification Group')
                </button>
            </div>
        </div>

        <div class="d-flex gap-3 flex-wrap justify-content-between align-items-center mb-4">
            <h5>@lang('Specifications')</h5>

        </div>

        <div class="row gy-4" id="specificationsWrapper"></div>

        <div class="card sticky-submit-button">
            <div class="card-body">
                <button type="submit" class="btn btn--lg btn--primary h-45 w-100">
                    @if (@$template->id)
                        @lang('Save Changes')
                    @else
                        @lang('Submit')
                    @endif
                </button>
            </div>
        </div>
    </form>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            const specificationsWrapper = $('#specificationsWrapper');
            const form = $('#speficationForm');
            const addGroupBtn = $('#addGroupBtn');
            const specifications = @json($template->specifications ?? []);
            const isEditForm = @json(@$template ? true : false);
            let groupCount = specifications.length ?? 0;

            const addAttributeClickHandler = function() {
                const container = $(this).parents('.specification-group');
                const groupCount = container.data('count')
                appendAndShowElement(container.find('.attributes'), buildSpecificationAttribute(groupCount));
                container.find('.attributes').sortable();
            }

            const buildSpecificationAttribute = (groupCount, value = '') => {
                return `
                        <div class="form-group attribute-item ui-state-default">
                            <div class="input-group">
                                <span class="input-group-text ui-icon ui-icon-arrowthick-2-n-s"><i class=" fa fa-arrows-alt"></i></span>
                                <input type="text" class="form-control" name="specification_group[${groupCount}][attributes][]" value="${value}" placeholder="@lang('Attribute Name')" required>
                                <button type="button" class="input-group-text btn--danger border-0 removeAttributeBtn"><i class="la la-times"></i></button>
                            </div>
                        </div>`;
            }

            const buildSpecificationGroup = (groupCount, value = '') => {
                return ` <div class="col-sm-12 specification-group" data-count="${groupCount}">
                            <div class="card" >
                                <div class="card-body">
                                    <button type="button" class="bg-transparent text-muted removeGroupBtn"><i class="la la-times me-0"></i></button>
                                    <div class="form-group">
                                        <label class="f-size--18">@lang('Group Name')</label>
                                        <input type="text" class="form-control" name="specification_group[${groupCount}][group_name]" value="${value}" required>
                                    </div>


                                    <button type="button" class="btn btn-sm btn--dark addAttributeBtn">
                                        <i class="la la-plus"></i> @lang('Add Attribute')
                                    </button>
                                    <div class="attributes mt-3"></div>
                                </div>
                            </div>
                        </div>
                `;
            }

            const removeSpecificationHandler = function() {
                if ($(this).parents('.attributes').find('.attribute-item').length == 1) {
                    notify('warning', 'At least one attribute is required for each group');
                    return;
                }
                $(this).closest('.attribute-item').remove();
                group.slideUp('fast', function() {
                    group.remove();
                });
            }

            const removeGroupButtonClickHandler = function() {
                const group = $(this).closest('.specification-group');
                groupCount--;
                group.slideUp('fast', function() {
                    group.remove();
                });
            }

            const addGroupBtnClickHandler = () => {
                const content = buildSpecificationGroup(groupCount);
                const specificationGroup = appendAndShowElement(specificationsWrapper, content, false);
                appendAndShowElement(specificationGroup.find('.attributes'), buildSpecificationAttribute(groupCount), false);

                $(specificationGroup)[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
                groupCount++;
            }

            const setExistingSpecifications = function() {
                specifications.forEach((specification, i) => {
                    const content = buildSpecificationGroup(i, specification.group_name);
                    const specificationGroup = appendAndShowElement(specificationsWrapper, content, false);
                    specification.attributes.forEach(element => {
                        const groupCount = specificationGroup.data('count');
                        appendAndShowElement(specificationGroup.find('.attributes'), buildSpecificationAttribute(groupCount, element), false);
                        specificationGroup.find('.attributes').sortable();
                    });
                });
            }

            if (isEditForm) {
                setExistingSpecifications();
            } else {
                addGroupBtnClickHandler();
            }


            specificationsWrapper.on('click', '.removeAttributeBtn', removeSpecificationHandler);
            addGroupBtn.on('click', addGroupBtnClickHandler);


            $(document).on('click', '.addAttributeBtn', addAttributeClickHandler);
            $(document).on('click', '.removeGroupBtn', removeGroupButtonClickHandler);

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .specification-group {
            position: relative;
        }

        .removeGroupBtn {
            top: 8px;
            right: 15px;
            position: absolute;
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .add-group-btn-wrapper {
            position: sticky;
            top: 0px;
            z-index: 1;
        }

        .attribute-item span.input-group-text {
            cursor: move;
        }
    </style>
@endpush
