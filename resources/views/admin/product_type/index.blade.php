@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $template)
                                    <tr>
                                        <td>{{ __($template->name) }}</td>
                                        <td>
                                            <a href="{{ route('admin.product.type.edit', $template->id) }}" data-resource="{{ $template }}" class="btn btn-sm btn-outline--primary editBtn"><i class="la la-pencil"></i> @lang('Edit')</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($templates->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($templates) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex gap-3">
        <x-search-form />
        <a href="{{ route('admin.product.type.create') }}" class="btn btn-outline--primary btn-sm flex-shrink-0">
            <i class="las la-plus"></i> @lang('Add New')
        </a>
    </div>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const modal = $('#specificationModal');
            const specificationsWrapper = $('#specificationsWrapper');
            const form = $('#speficationForm');
            const formAction = `{{ route('admin.product.type.store') }}`;
            const addGroupBtn = $('#addGroupBtn');

            const addButtonClickHandler = () => {
                form[0].action = formAction;
                modal.modal('show');
            }

            const editButtonClickHandler = function() {
                const data = $(this).data('resource');
                form[0].action = formAction + '/' + data.id;
                data.specifications.forEach((specification, i) => {
                    appendSpecificationField(specification);
                });
                modal.find('[name=name]').val(data.name);

                $("#specificationsWrapper").sortable();
                modal.modal('show');
            }

            const onModalHideHandler = () => {
                modal.find('input:not([name="_token"]').val('');
                specificationsWrapper.empty();
            }

            const addFieldBtnClickHandler = () => {
                appendSpecificationField('');
                $("#specificationsWrapper").sortable();
            }

            const appendSpecificationField = (value = '') => {
                const content = buildSpecificationElement(value);
                $(content).appendTo(specificationsWrapper).hide().slideDown('slow');
            }

            const buildSpecificationElement = (value = '') => {
                return `
                        <div class="form-group specification-group ui-state-default">
                            <div class="input-group">
                                <span class="input-group-text ui-icon ui-icon-arrowthick-2-n-s"><i class=" fa fa-arrows-alt"></i></span>
                                <input type="text" class="form-control" name="specifications[]" value="${value}" placeholder="@lang('Attribute Name')" required>
                                <button type="button" class="input-group-text btn--danger border-0 remove-specification"><i class="la la-times"></i></button>
                            </div>
                        </div>`;

            }

            const removeSpecificationHandler = function() {
                const group = $(this).closest('.specification-group');
                group.slideUp('slow', function() {
                    group.remove();
                });
            }


            const addGroupBtnClickHandler = () => {
                form.append(`

                    `);
            }

            addGroupBtn.on('click', addGroupBtnClickHandler)
            specificationsWrapper.on('click', '.remove-specification', removeSpecificationHandler);
            $('.addBtn').on('click', addButtonClickHandler);
            $('.editBtn').on('click', editButtonClickHandler);
            modal.on('hidden.bs.modal', onModalHideHandler);
            $('#addFieldBtn').on('click', addFieldBtnClickHandler);

        })(jQuery);
    </script>
@endpush
