@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-lg-4 sticky-position">
            <div class="card b-radius--10 position-sticky top-30">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h5 class="card-title formTitle mb-0">@lang('Add New Value')</h5>
                    <button type="reset" class="btn btn-sm btn-outline--primary resetBtn d-none" form="attributeForm"> <i class="las la-plus"></i>@lang('Add New')</button>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.attribute.values.store', $attribute->id) }}" method="POST" id="attributeForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="value_id" value="">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control nameField" name="name" required />
                        </div>
                        <div class="form-group">
                            <label>@lang('Value')</label>
                            @if ($attribute->type == Status::ATTRIBUTE_TYPE_COLOR)
                                <div div class= "input-group">
                                    <span class="input-group-text h-45 p-0 overflow-hidden">
                                        <input type='text' class="form-control colorPicker" value="e81f1f" />
                                    </span>
                                    <input type="text" class="form-control colorCode valueField" name="value" value="e81f1f" required />
                                </div>
                                <small class="text-muted"><i class="la la-info-circle"></i> @lang('The color code must be in HEX format')</small>
                            @elseif($attribute->type == Status::ATTRIBUTE_TYPE_IMAGE)
                                <x-image-uploader name="value" type="attribute" class="w-100" :showInfo="false" />
                            @else
                                <input type="text" class="form-control nameField" name="value" required />
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45" form="attributeForm">@lang('Submit')</button>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Value')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse($attributeValues as $attributeValue)
                                    <tr>
                                        <td>{{ __($attributeValue->name) }}</td>
                                        <td>
                                            @if ($attribute->type == Status::ATTRIBUTE_TYPE_COLOR)
                                                <span class="color-value" style="background-color: #{{ $attributeValue->value }}">&nbsp;</span>
                                            @elseif($attribute->type == Status::ATTRIBUTE_TYPE_IMAGE)
                                                <span class="color-value">
                                                    <a href="{{ getImage(getFilePath('attribute') . '/' . @$attributeValue->value, getFileSize('attribute')) }}" class="image-popup">
                                                        <img src="{{ getImage(getFilePath('attribute') . '/' . @$attributeValue->value, getFileSize('attribute')) }}" alt="@lang('image')">
                                                    </a>
                                                </span>
                                            @else
                                                {{ $attributeValue->value }}
                                            @endif
                                        </td>

                                        <td>
                                            <button class="btn btn-outline--primary editAttribute" data-item="{{ $attributeValue }}" @if ($attribute->type == 3)  @endif>
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>

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

                @if ($attributeValues->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($attributeValues) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form></x-search-form>
    <x-back route="{{ route('admin.attribute.all') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            const form = $('#attributeForm');

            const addSpectrum = () => {
                $('.colorPicker').spectrum({
                    color: $(this).data('color'),
                    change: function(color) {
                        $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                    }
                });
            };

            addSpectrum();

            $('.colorCode').on('input', function(){
                $('.colorPicker').spectrum('set', $(this).val());
            });

            const editAttributeClickHandler = function() {
                const data = $(this).data('item');
                $('.resetBtn').removeClass('d-none');
                $('.formTitle').text(`@lang('Update Value')`);
                $('[name=name]').val(data.name);

                @if ($attribute->type == Status::ATTRIBUTE_TYPE_IMAGE)
                    $('[name=value]').prop('required', false);
                    const imagePath = `{{ getFilePath('attribute') }}`;
                    const basePath = `{{ url('') }}`;
                    const image = basePath + '/' + imagePath + "/" + data.value;
                    $('.image-upload-preview').css("background-image", `url(${image})`);
                @elseif ($attribute->type == Status::ATTRIBUTE_TYPE_COLOR)
                    $('.colorPicker').val(data.value);
                @endif

                @if ($attribute->type != Status::ATTRIBUTE_TYPE_IMAGE)
                    $('[name=value]').val(data.value);
                @endif

                $('[name=value_id]').val(data.id);
                addSpectrum();
            }

            form.on('reset', function() {
                $('.formTitle').text(`@lang('Add New Value')`);
                $('.resetBtn').addClass('d-none');
                $('.colorPicker').val('e81f1f');
                $('[name=value_id]').val('');
                @if ($attribute->type == Status::ATTRIBUTE_TYPE_IMAGE)
                    let defaultImg = "{{ getImage(null, getFileSize('attribute')) }}";
                    $('.image-upload-preview').css('background-image', `url("${defaultImg}")`);
                @endif
                addSpectrum();
            });

            $('.editAttribute').on('click', editAttributeClickHandler);
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .image--uploader,
        .image-upload-wrapper {
            height: 45px;
        }

        .image-upload-wrapper {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .image--uploader .image-upload-preview {
            position: absolute;
            height: 43px;
            width: 43px;
            border-radius: 5px;
            border: none;
        }

        .color-value {
            width: 30px;
            height: 30px;
            display: inline-flex;
            border-radius: 50%;
            border: 1px solid #ededed;
            overflow: hidden;
        }

        .top-30 {
            top: 30px;
        }

        .sticky-position {
            z-index: 9;
            top: 0;
            position: sticky
        }
    </style>
@endpush
