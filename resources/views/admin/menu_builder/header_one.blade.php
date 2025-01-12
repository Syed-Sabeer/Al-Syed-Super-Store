@extends('admin.menu_builder.layout')
@section('menu-content')
    <form action="{{ route('admin.menu.builder.header.one.update') }}" method="post" class="d-flex flex-column gap-3" id="menuBuilderForm">
        @csrf
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <label>@lang('Status')</label>
                        <x-toggle-switch name="status" :checked="$setting->status == 'on'" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>@lang('Predefined Options')</h5>
                </div>
                <div class="card-body d-flex gap-3 flex-column">
                    <div class="d-flex justify-content-between gap-3 border p-3 rounded align-items-center">
                        <label class="m-0">@lang('Language Option')</label>
                        <x-toggle-switch name="language_option" :checked="$setting->language_option == 'on'" />
                    </div>

                    <div class="d-flex justify-content-between gap-3 border p-3 rounded align-items-center">
                        <label class="m-0">@lang('User Option')</label>
                        <x-toggle-switch name="user_option" :checked="$setting->user_option == 'on'" />
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">

                <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                    <h5 class="card-title">@lang('Links')</h5>

                    <div class="text-end">
                        <button type="button" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Add New')</button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label>@lang('Links Position')</label>
                        </div>

                        <div class="col-md-9">
                            <select name="links_position" class=form-control form-select">
                                <option value="left" @selected(@$setting->links_position == 'left')>@lang('Left')</option>
                                <option value="right" @selected(@$setting->links_position == 'right')>@lang('Right')</option>
                            </select>
                        </div>
                    </div>

                    <div id="menus" class="border-top pt-4 mt-4 d-flex flex-column gap-3">
                        @if ($menus)
                            @foreach ($menus as $key => $menu)
                                <div class="d-flex gap-2 single-menu ui-state-default align-items-end" data-index="{{ $key }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ui-icon  text-muted"><i class="fa fa-grip-vertical"></i></span>
                                        <div class="input-group">
                                            <span class="input-group-text">@lang('Name')</span>
                                            <input type="text" name="links[{{ $key }}][name]" class="form-control text-dark" value="{{ $menu->name }}" required>
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-text">@lang('Link')</span>
                                        <input type="text" name="links[{{ $key }}][url]" class="form-control" value="{{ $menu->url }}" required>
                                        <button type="button" class="input-group-text ms-2 btn btn--light removeBtn"><i class="las la-trash-alt me-0"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn--primary h-45 mt-3 w-100" form="menuBuilderForm">@lang('Submit')</button>
    </form>
@endsection

@push('script')
    <script>
        (function($) {
            'use strict';
            let index = $('#menuBuilderForm').find('.single-menu').length;

            $('.addBtn').on('click', function() {

                index = $('#menus').find('.single-menu').last()[0]?.dataset.index ?? 0;
                index++;

                $(`<div class="d-flex gap-2 single-menu ui-state-default align-items-end" data-index="${index}">
                        <div class="d-flex align-items-center gap-2">
                            <span class="ui-icon  text-muted mb-3"><i class="fa fa-grip-vertical"></i></span>
                            <div class="input-group">
                                <span class="input-group-text">@lang('Name')</span>
                                <input type="text" name="links[${index}][name]" class="form-control text-dark" value="" required>
                            </div>
                        </div>

                       <div class="input-group">
                            <span class="input-group-text">@lang('Link')</span>
                            <input type="text" name="links[${index}][url]" class="form-control text-dark" required>
                            <button type="button" class="input-group-text ms-2 btn btn--light removeBtn"><i class="las la-trash-alt me-0"></i></button>
                        </div>
                    </div>
                `).appendTo($('#menus'));
                index++;
            });

            $(document).on('click', '.removeBtn', function() {
                index--;
                $(this).parents('.single-menu').remove();
            });

            $('#menus').sortable();
            $('#menus').disableSelection();
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

        #menus input {
            background: #fff !important;
        }
    </style>
@endpush
