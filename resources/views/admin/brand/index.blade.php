@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Logo')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Products')</th>
                                    <th>@lang('Is Featured')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse($brands as $brand)
                                    <tr>
                                        <td>

                                            <div class="table-thumb">
                                                <a href="{{ $brand->logo() }}" class="image-popup">
                                                    <img src="{{ $brand->logo() }}" alt="@lang('image')">
                                                </a>
                                            </div>

                                        </td>
                                        <td>{{ __($brand->name) }}</td>

                                        <td>
                                            <a href="{{ route('admin.products.all') }}?brand_id={{ $brand->id }}">{{ $brand->products_count }}</a>
                                        </td>

                                        <td>
                                            <x-toggle-switch class="change_status" :checked="$brand->is_featured" data-id="{{ $brand->id }}" />
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                @php
                                                    $brand->image_with_path = $brand->logo();
                                                @endphp

                                                @if (!Route::is('admin.brand.trashed'))
                                                    <button type="button" class="btn btn-sm btn-outline--primary cuModalBtn editBtn"data-resource="{{ $brand }}" data-modal_title="@lang('Edit Brand')" data-has_status="1">
                                                        <i class="la la-pencil"></i>@lang('Edit')
                                                    </button>
                                                @endif

                                                @if (!$trashed)
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.brand.delete', $brand->id) }}" data-question="@lang('Are you sure to delete this brand?')"><i class="las la-trash-alt"></i>@lang('Delete')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.brand.delete', $brand->id) }}" data-question="@lang('Are you sure to restore this brand?')"><i class="las la-trash-restore"></i>@lang('Restore')
                                                    </button>
                                                @endif
                                            </div>
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
                </div>

                @if ($brands->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($brands) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="cuModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Brand')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="@lang('Close')">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data" id="brandForm">
                        @csrf

                        <div class="row">
                            <div class="col-xl-3 col-lg-4">
                                <label class="logo-label">@lang('Logo')</label>
                                <x-image-uploader class="w-100" name="image_input" type="brand" />
                            </div>

                            <div class="col-xl-9 col-lg-8">
                                <div class="form-group row">
                                    <div class="col-lg-3 col-xl-2">
                                        <label>@lang('Name')</label>
                                    </div>
                                    <div class="col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" value="{{ old('name') }}" name="name" required />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-3 col-xl-2">
                                        <label>@lang('Is Featured')</label>
                                    </div>
                                    <div class="col-lg-9 col-xl-10">
                                        <select name="is_featured" class="form-control">
                                            <option value="{{ Status::NO }}">@lang('No')</option>
                                            <option value="{{ Status::YES }}">@lang('Yes')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-3 col-xl-2">
                                        <label>@lang('Meta Title')</label>
                                    </div>
                                    <div class="col-lg-9 col-xl-10">
                                        <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-3 col-xl-2">
                                        <label>@lang('Meta Description')</label>
                                    </div>
                                    <div class="col-lg-9 col-xl-10">
                                        <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description') }} </textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-3 col-xl-2">
                                        <label>@lang('Meta Keywords')</label>
                                    </div>
                                    <div class="col-lg-9 col-xl-10">
                                        <select name="meta_keywords[]" class="form-control select2-auto-tokenize" multiple="multiple"></select>
                                        <small class="form-text text-muted">
                                            <i class="las la-info-circle"></i>
                                            @lang('Type , or hit enter to seperate keywords')
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45" form="brandForm">@lang('Submit')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 has-search-form">
        <div class="flex-grow-1">
            <x-search-form></x-search-form>
        </div>
        @if (!$trashed)
            <!-- Modal Trigger Button -->
            <button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn flex-shrink-0 flex-grow-1 addBtn" data-modal_title="@lang('Add New Brand')" data-default_image="{{ getImage(null, null) }}">
                <i class="las la-plus"></i>@lang('Add New')
            </button>

            <a href="{{ route('admin.brand.trashed') }}" class="btn btn-sm btn-outline--danger h-45 flex-shrink-0 flex-grow-1">
                <i class="las la-trash-restore-alt"></i>@lang('Trashed')
            </a>
        @else
            <x-back route="{{ route('admin.brand.all') }}"></x-back>
        @endif
    </div>
@endpush

@push('script')
    <script>
        'use strict';
        (function($) {

            $('#cuModal').on('shown.bs.modal', function(e) {
                $(document).off('focusin.modal');
            });

            $('.editBtn').on('click', function() {
                var modal = $('#cuModal');
                modal.find('.select2-auto-tokenize').empty();
                var brand = $(this).data('resource');
                modal.find('.logo-label').removeClass('required');
                $.each(brand.meta_keywords, function(i, item) {
                    modal.find('.select2-auto-tokenize').append($('<option>', {
                        value: item,
                        text: item,
                    }));
                });
                modal.find('.select2-auto-tokenize').val(brand.meta_keywords);
            });

            $('.change_status').on('change', function() {
                var id = $(this).data('id');

                var data = {
                    _token: `{{ csrf_token() }}`,
                };

                $.ajax({
                    url: `{{ route('admin.brand.status', '') }}/${$(this).data('id')}`,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        notify(response.status, response.message);
                    }
                });
            });

            $('.addBtn').on('click', function() {
                $('#cuModal').find('.logo-label').addClass('required');
            });


        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .table-thumb {
            width: 75px;
            height: 50px;
        }

        @media (max-width: 991px) {
            .table-thumb {
                margin-left: auto;
            }
        }
    </style>
@endpush
