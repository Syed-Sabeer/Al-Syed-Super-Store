@extends('Template::layouts.user')

@section('panel')
    <div class="row gy-4 justify-content-center">

        <div class="col-12">
            <div class="card custom--card bg--light">
                <div class="p-3 d-flex align-items-center gap-3">
                    <span class="icon">
                        <i class="la text--lg la-info-circle"></i>
                    </span>
                    <p class="mb-0">@lang('For the security of your account, it’s important to update your password periodically. Regular updates help keep your account safe from unauthorized access.')</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card custom--card">
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">@lang('Current Password')</label>
                            <input type="password" class="form--control" name="current_password" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Password')</label>
                            <input type="password" class="form--control @if (gs('secure_password')) secure-password @endif" name="password" required autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Confirm Password')</label>
                            <input type="password" class="form--control" name="password_confirmation" required autocomplete="current-password">
                        </div>
                        <button type="submit" class="btn btn--base h-45 w-100">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            @if (gs('secure_password'))
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').focus(function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').focusout(function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif
        })(jQuery);
    </script>
@endpush
