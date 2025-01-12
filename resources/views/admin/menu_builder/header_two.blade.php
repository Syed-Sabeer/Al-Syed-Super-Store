@extends('admin.menu_builder.layout')
@section('menu-content')
    <form action="{{ route('admin.menu.builder.header.two.update') }}" method="post" class="d-flex flex-column gap-3" id="menuBuilderForm">
        @csrf
        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between">
                <h5>@lang('Status') &nbsp; <x-toggle-switch name="status" :checked="@$settings->status == 'on'" /></h5>
            </div>

            <div class="card-body d-flex gap-3 flex-column" id="menuOptions">
                @foreach ($settings->group as $key => $group)
                    @if ($key == 'logo_widget')
                        <div class="d-flex align-items-center gap-2 border p-3 rounded bg-white" id="categoryWidget">
                            <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                            <div class="d-flex justify-content-between flex-grow-1">
                                <span class="flex-grow-1">
                                    <input type="hidden" name="group[{{ $key }}][name]" value="logo">
                                    <img src="{{ siteLogo('dark') }}" alt="logo" width="140">
                                </span>
                                <x-toggle-switch name="group[{{ $key }}][status]" :checked="@$group->status == 'on'" />
                            </div>
                        </div>
                    @elseif ($key == 'search_widget')
                        <div class="d-flex align-items-center gap-2 border p-3 rounded bg-white" id="categoryWidget">
                            <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                            <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                <span class="flex-grow-1 max-width-320 ">
                                    <input type="hidden" name="group[{{ $key }}][name]" value="logo">
                                    <div class="border rounded me-3 px-3 py-2 d-flex justify-content-between align-items-center">@lang('Search')<i class="la la-search"></i></div>
                                </span>
                                <x-toggle-switch name="group[{{ $key }}][status]" :checked="@$group->status == 'on'" />
                            </div>
                        </div>
                    @elseif ($key == 'widgets')
                        <div class="border rounded p-3 d-flex flex-column gap-3 bg-white" id="otherWidgets">
                            <h6>@lang('Widgets')</h6>
                            @foreach ($settings->group->widgets as $widget)
                                <div class="d-flex align-items-center gap-2 border p-2 rounded">
                                    <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                                    <div class="d-flex align-items-center justify-content-between flex-grow-1">
                                        <span class="flex-grow-1">
                                            {{ __($widget->name) }}
                                            <input type="hidden" name="group[widgets][{{ $loop->index }}][name]" value="{{ $widget->name }}">
                                            <input type="hidden" name="group[widgets][{{ $loop->index }}][key]" value="{{ $widget->key }}">
                                        </span>
                                        <x-toggle-switch name="group[widgets][{{ $loop->index }}][status]" :checked="@$widget->status == 'on'" />
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




@push('script')
    <script>
        (function($) {
            'use strict';
            $('#menuLinks').sortable();
            $('#menuLinks').disableSelection();
            $("#menuOptions").sortable();
            $("#menuOptions").disableSelection();
            $("#otherWidgets").sortable();
            $("#otherWidgets").disableSelection();
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

        .max-width-320 {
            max-width: 320px;
        }
    </style>
@endpush
