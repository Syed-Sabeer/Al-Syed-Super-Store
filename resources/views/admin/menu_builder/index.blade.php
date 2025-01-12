@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-lg-12">
            @include('admin.menu_builder._headers')
        </div>

        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between border p-3 rounded">
                        <span>@lang('Footer Menu')</span>
                        <a href="{{ route('admin.menu.builder.footer') }}" class="btn btn-sm btn--light"><i class="las la-cog m-0"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/jquery.ui.touch-punch.min.js') }}"></script>
@endpush
