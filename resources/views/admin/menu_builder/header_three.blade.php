@extends('admin.menu_builder.layout')
@section('menu-content')
    <form action="{{ route('admin.menu.builder.header.three.update') }}" method="post" class="d-flex flex-column gap-3" id="menuBuilderForm">
        @csrf
        <div class="card">
            <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <h6>@lang('Status') &nbsp; <x-toggle-switch name="status" :checked="@$settings->status == 'on'" /></h6>

                <div class="d-flex gap-3 align-items-center">
                    <div div class= "input-group color-picker">
                        <span class="input-group-text p-0 overflow-hidden">
                            <input type='text' class="form-control colorPicker" value="{{ @$settings->background_color }}" />
                        </span>
                        <input type="text" class="form-control colorCode" name="background_color" value="{{ @$settings->background_color }}" />
                    </div>
                    <small class="text-muted" title="If you want to manage background color at this level, you can do so here. The base color set in the general settings will be applied unless overridden."><i class="la la-info-circle"></i> @lang('')</small>
                </div>
            </div>

            <div class="card-body d-flex gap-3 flex-column" id="menuOptions">
                @foreach ($settings->group as $key => $group)
                    @if ($key == 'category_widget')
                        <div class="d-flex align-items-center gap-2 border p-3 rounded bg-white" id="categoryWidget">
                            <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                            <div class="d-flex justify-content-between flex-wrap gap-3 flex-grow-1 align-items-center">
                                <span class="flex-grow-1">
                                    @lang('Category Dropdown Menu')
                                    <input type="hidden" name="group[category_widget][name]" value="Categories">
                                </span>

                                <div class="d-flex gap-3 align-items-center">

                                    <div>
                                        <x-toggle-switch name="group[category_widget][status]" :checked="@$group->status == 'on'" />
                                    </div>

                                    <div class="input-group color-picker">
                                        <span class="input-group-text p-0 overflow-hidden">
                                            <input type='text' class="form-control colorPicker" value="{{ @$group->background_color }}" />
                                        </span>
                                        <input type="text" class="form-control colorCode" name="group[category_widget][background_color]" value="{{ @$group->background_color }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($key == 'links')
                        <div class="d-flex flex-column gap-3 border p-3 rounded bg-white">
                            <div class="d-flex justify-content-between gap-2 flex-wrap">
                                <h6>@lang('Primary Menu')</h6>
                                <button type="button" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
                            </div>
                            <div class="d-flex flex-column gap-3" id="menuLinks">
                                @foreach ($settings->group->links as $key => $menu)
                                    <div class="d-flex gap-2 single-menu ui-state-default align-items-center" data-index="{{ $key }}">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                                            <div class="input-group">
                                                <span class="input-group-text">@lang('Name')</span>
                                                <input type="text" name="group[links][{{ $key }}][name]" class="form-control bg-white text-dark" value="{{ $menu->name }}" required>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <span class="input-group-text">@lang('Link')</span>
                                            <input type="text" name="group[links][{{ $key }}][url]" class="form-control" value="{{ $menu->url }}" required>
                                            <button type="button" class="input-group-text ms-2 btn btn--light removeBtn"><i class="las la-trash-alt me-0"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif ($key == 'widgets')
                        <div class="border rounded p-3 d-flex flex-column gap-3 bg-white" id="otherWidgets">
                            @foreach ($settings->group->widgets as $widget)
                                <div class="d-flex align-items-center gap-2 border p-2 rounded">
                                    <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                                    <div class="d-flex justify-content-between flex-grow-1 align-items-center">
                                        <span class="flex-grow-1">
                                            {{ __($widget->name) }}
                                            <input type="hidden" name="group[widgets][{{ $loop->index }}][name]" value="{{ $widget->name }}">
                                            <input type="hidden" name="group[widgets][{{ $loop->index }}][key]" value="{{ $widget->key }}">
                                        </span>

                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <x-toggle-switch name="group[widgets][{{ $loop->index }}][status]" :checked="@$widget->status == 'on'" />
                                            </div>

                                            <div class="input-group color-picker">
                                                <span class="input-group-text p-0 overflow-hidden">
                                                    <input type='text' class="form-control colorPicker" value="{{ @$widget->background_color }}" />
                                                </span>
                                                <input type="text" class="form-control colorCode" name="group[widgets][{{ $loop->index }}][background_color]" value="{{ @$widget->background_color }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
    </form>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush


@push('script')
    <script>
        (function($) {
            'use strict';
            let index = $('#menuBuilderForm').find('.single-menu').length;

            $('.addBtn').on('click', function() {
                const indexArray = $('#menuBuilderForm').find('.single-menu').map((i, e) => {
                    return $(e).data('index');
                }).get();

                index = indexArray.length ? Math.max(...indexArray) + 1 : 1;

                $(`<div class="d-flex gap-2 single-menu ui-state-default align-items-center" data-index="${index}">
                        <div class="d-flex align-items-center gap-2">
                            <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                            <div class="input-group">
                                <span class="input-group-text">@lang('Name')</span>
                                <input type="text" name="group[links][${index}][name]" class="form-control bg-white text-dark" value="" required>
                            </div>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text">@lang('Link')</span>
                            <input type="text" name="group[links][${index}][url]" class="form-control bg-white text-dark" required>
                            <button type="button" class="input-group-text ms-2 btn btn--light removeBtn"><i class="las la-trash-alt me-0"></i></button>
                        </div>
                    </div>
                `).appendTo($('#menuLinks'));

                index++;
            });

            $(document).on('click', '.removeBtn', function() {
                index--;
                $(this).parents('.single-menu').remove();
            });

            $('#menuLinks').sortable();
            $("#menuOptions").sortable();
            $("#otherWidgets").sortable();

            const addSpectrum = () => {
                $('.colorPicker').spectrum({
                    color: $(this).data('color'),
                    change: function(color) {
                        $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                    }
                });
            };

            addSpectrum();

            $('.colorCode').on('input', function() {
                $('.colorPicker').spectrum('set', $(this).val() ?? transparent);
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .input-group .form-control {
            border-top-right-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
        }

        .input-group .input-group-text {
            border-top-left-radius: 5px !important;
            border-bottom-left-radius: 5px !important;
        }

        .colorPicker {
            height: 30px;
            width: 20px;
        }

        .colorCode {
            width: 68px !important;
            height: 30px !important;
            padding: 5px !important;
        }

        .sp-preview {
            width: 20px;
            height: 30px;
        }

        .color-picker .input-group-text {
            height: 30px;
        }

        #menuOptions, #menuOptions .input-group-text {
            font-size: 0.875rem;
        }
    </style>
@endpush
