@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xl-4 col-xxl-3">
            @include('admin.menu_builder._headers')
        </div>
        <div class="col-xl-8 col-xxl-9">
            @yield('menu-content')
        </div>
    </div>

    <div class="modal fade" id="linksModalId" tabindex="-1" role="dialog" aria-labelledby="linksModalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Links')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.menu_builder.links')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.menu.builder.all') }}" />

    <button type="button" class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#linksModalId">
        <i class="la la-link"></i> @lang('View Links')
    </button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/jquery.ui.touch-punch.min.js') }}"></script>
@endpush


<!-- Button trigger modal -->
