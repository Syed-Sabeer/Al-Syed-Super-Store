@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $content = getContent('profile_complete_page.content', true);
    @endphp


    <div class="py-60">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <div class="card custom--card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ __(@$content->data_values->title) }}</h5>
                            <p class="bg-light p-3 rounded">{{ __(@$content->data_values->description) }}</p>
                            <form method="POST" action="{{ route('user.data.submit') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Username')</label>
                                            <input type="text" class="form-control form--control checkUser" name="username" value="{{ old('username') }}">
                                            <small class="text-danger usernameExist"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Country')</label>
                                            <select name="country" class="form-control form--control select2" required>
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile')</label>
                                            <div class="input-group">
                                                <span class="input-group-text mobile-code"></span>
                                                <input type="hidden" name="mobile_code">
                                                <input type="hidden" name="country_code">
                                                <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser ps-0" required>
                                            </div>
                                            <small class="text-danger mobileExist"></small>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('State')</label>
                                        <input type="text" class="form-control form--control" name="state" value="{{ old('state') }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('City')</label>
                                        <input type="text" class="form-control form--control" name="city" value="{{ old('city') }}">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Zip Code')</label>
                                        <input type="text" class="form-control form--control" name="zip" value="{{ old('zip') }}">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Address')</label>
                                        <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}">
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" class="btn btn--base h-45 w-100">
                                        @lang('Submit')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {
            $('.select2-selection__rendered').removeAttr("data-bs-original-title");

            $.each($('.select2'), function() {
                $(this)
                    .wrap(`<div class="position-relative"></div>`)
                    .select2({
                        dropdownParent: $(this).parent(),
                    });
            });

            @if ($mobileCode)
                $('select[name=country]').val($(`option[data-code={{ $mobileCode }}]`).val()).select2({
                    dropdownParent: $('select[name=country]').parent()
                });
            @endif

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkUser(value, name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkUser(value, name);
            });

            function checkUser(value, name) {
                var url = '{{ route('user.checkUser') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.field} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            }
        })(jQuery);
    </script>
@endpush
