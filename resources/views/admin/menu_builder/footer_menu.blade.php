@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-md-8 col-xxl-9">


            <form action="{{ route('admin.menu.builder.footer.update') }}" method="post" class="d-flex flex-column gap-3" id="menuBuilderForm">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <h6>@lang('Background Color')</h6>
                    </div>
                </div>

                @if ($menus)
                    @foreach ($menus as $key => $menu)
                        <div class="card single-group">
                            <div class="card-body">
                                <div class="action-wrapper d-flex justify-content-between mb-3">
                                    <button type="button" class="btn btn-outline--primary addNewLink"><i class="las la-plus"></i>@lang('Add New Link')</button>
                                    <button type="button" class="remove-btn removeGrpBtn"><i class="las la-times"></i></button>
                                </div>

                                <div class="field-wrapper">

                                    <div class="input-group mb-3">
                                        <span class="input-group-text">@lang('Group Title')</span>
                                        <input type="text" class="form-control group-title" name="groups[{{ $key }}][title]" value="{{ $menu->title }}" required>
                                    </div>

                                    <h6 class="mb-3">@lang('Links')</h6>
                                    <div class="links-container">
                                        @foreach ($menu->links as $menuKey => $link)
                                            <div class="link-wrapper ui-state-default align-items-end d-flex justify-content-between align-items-center gap-3">
                                                <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                                                <div class="input-group">
                                                    <span class="input-group-text">@lang('Link Title')</span>
                                                    <input type="text" name="groups[{{ $key }}][links][{{ $menuKey }}][name]" value="{{ $link->name }}" class="form-control link-title" required>
                                                </div>

                                                <div class="input-group">
                                                    <span class="input-group-text">@lang('Link')</span>

                                                    <input type="text" name="groups[{{ $key }}][links][{{ $menuKey }}][url]" value="{{ $link->url }}" class="form-control link-url" required>

                                                    <button type="button" class="input-group-text ms-2 btn btn--light removeLinkBtn"><i class="las la-trash-alt me-0"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </form>
            <button type="submit" class="btn btn--primary h-45 mt-3 w-100" form="menuBuilderForm">@lang('Submit')</button>
        </div>

        <div class="col-md-4 col-xxl-3">
            <div class="card position-sticky">
                <div class="card-header">
                    <h5 class="card-title">@lang('Available Links')</h5>
                </div>

                <div class="card-body sticky-links">
                    @include('admin.menu_builder.links')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn--primary addNewGroupBtn"><i class="las la-plus"></i>@lang('Add New Group')</button>
    <x-back route="{{ route('admin.menu.builder.all') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';

            $('.addNewGroupBtn').on('click', function() {
                let html = `<div class="card single-group">
                        <div class="card-body position-relative">
                            <div class="action-wrapper d-flex justify-content-between mb-3">
                                <div class="text-end">
                                    <button type="button" class="btn btn-outline--primary addNewLink"><i class="las la-plus"></i>@lang('Add New Link')</button>
                                </div>

                                <button type="button" class="remove-btn removeGrpBtn"><i class="las la-times"></i></button>
                            </div>

                            <div class="field-wrapper">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">@lang('Group Title')</span>
                                    <input type="text" class="form-control group-title" name="groups[0][title]" required>
                                </div>

                                <h6 class="mb-3">@lang('Links')</h6>
                                <div class="links-container">
                                    <div class="link-wrapper ui-state-default align-items-end align-items-end d-flex justify-content-between align-items-center gap-3">
                                        <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                                        <div class="input-group">
                                            <span class="input-group-text">@lang('Link Title')</span>
                                            <input type="text" name="groups[0][links][0][name]" class="form-control link-title" required>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text">@lang('Link')</span>
                                            <input type="text" name="groups[0][links][0][url]" class="form-control link-url" required>
                                            <button type="button" class="input-group-text ms-2 btn btn--light removeLinkBtn"><i class="las la-trash-alt me-0"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                const appended = appendAndShowElement($('#menuBuilderForm'), html);
                $(appended)[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
                resetIndex();
                $(appended).find('.links-container').sortable();
            });

            $(document).on('click', '.addNewLink', function() {
                $(this).parents('.single-group').find('.links-container').append(`
                <div class="link-wrapper align-items-end d-flex gap-3 justify-content-between align-items-center">
                    <span class="ui-icon bg-transparent"><i class="fa fa-grip-vertical"></i></span>
                    <div class="input-group">
                        <span class="input-group-text">@lang('Link Title')</span>
                        <input type="text" name="groups[0][links][0][name]" class="form-control link-title" required>
                    </div>

                    <div class="input-group">
                        <span class="input-group-text">@lang('Link')</span>
                        <input type="text" name="groups[0][links][0][url]" class="form-control link-url" required>
                        <button type="button" class="input-group-text ms-2 btn btn--light removeLinkBtn"><i class="las la-trash-alt me-0"></i></button>
                    </div>
                </div>
                `);

                resetIndex();
            });

            $(document).on('click', '.removeLinkBtn', function() {
                $(this).parents('.link-wrapper').remove();
                resetIndex();
            });

            $(document).on('click', '.removeGrpBtn', function() {
                $(this).parents('.single-group').remove();
                resetIndex();
            });

            function resetIndex() {
                let singleGroups = $('.single-group');
                $.each(singleGroups, function(groupIndex, group) {
                    $(group).find('.group-title').attr('name', `groups[${groupIndex}][title]`);

                    let linkWrappers = $(group).find('.link-wrapper');
                    $.each(linkWrappers, function(wrapIndex, linkWrapper) {
                        $(linkWrapper).find('.link-title').attr('name', `groups[${groupIndex}][links][${wrapIndex}][name]`);
                        $(linkWrapper).find('.link-url').attr('name', `groups[${groupIndex}][links][${wrapIndex}][url]`);
                    });

                });
            }

            $(window).on('scroll', function() {
                if ($(window).scrollTop() > 74) {
                    $('.bodywrapper__inner div').first().addClass('bg-white p-3 rounded mb-3')
                } else {
                    $('.bodywrapper__inner div').first().removeClass('bg-white p-3 rounded mb-3')
                }
            });

            $('.links-container').each((i, e) => {
                $(e).sortable();
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .links-container {
            display: flex;
            flex-direction: column;
            gap: .8rem;
        }

        .remove-btn {
            color: #252525;
            background-color: #f5f5f5;
            border-radius: 5px;
            font-size: 0.875rem;
            width: 30px;
            height: 30px;
        }

        .input-group-text {
            font-size: 0.875rem;
        }

        .body-wrapper {
            position: relative;
            z-index: 1;
        }

        .bodywrapper__inner>div:first-child {
            position: sticky;
            top: 0px;
            z-index: 9;
        }

        .links-container .input-group .form-control {
            border-top-right-radius: 5px !important;
            border-bottom-right-radius: 5px !important;
        }

        .input-group .input-group-text {
            border-top-left-radius: 5px !important;
            border-bottom-left-radius: 5px !important;
        }

        .card.position-sticky {
            top: 80px;
        }

        #menuBuilderForm .form-control {
            background: #fff;
        }

        .sticky-links {
            height: calc(100vh - 150px);
            overflow-y: auto;
        }
    </style>
@endpush
