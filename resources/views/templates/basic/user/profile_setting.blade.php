@extends('Template::layouts.user')

@section('panel')
        <div class="row gy-4">
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <div class="user-profile">
                            <div class="thumb">
                                <img id="imagePreview" src="{{ getImage(null) }}" data-src="{{ getAvatar(getFilePath('userProfile') . '/' . $user->image) }}" class="lazyload" alt="@lang('user')">
                                <label for="file-input" class="file-input-btn">
                                    <i class="la la-edit"></i>
                                </label>
                            </div>
                            <div class="user-profile-content">
                                <h6 class="title">{{ $user->fullname }}</h6>
                                <p class="d-flex align-items-center gap-2 mb-0">
                                    <span><i class="las la-user-alt"></i></span>
                                    <span> {{ $user->username }}</span>
                                </p>
                                <ul class="user-profile__info">
                                    @if ($user->email)
                                        <li>
                                            <span class="icon"><i class="las la-envelope"></i></span>
                                            <span class="text">{{ $user->email }}</span>
                                        </li>
                                    @endif

                                    @if ($user->mobileNumber)
                                        <li>
                                            <span class="icon"><i class="las la-phone"></i></span>
                                            <span class="text">{{ $user->mobileNumber }}</span>
                                        </li>
                                    @endif

                                    @if (@$user->country_name)
                                        <li>
                                            <span class="icon"><i class="las la-globe"></i></span>
                                            <span class="text">{{ @$user->country_name }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <h5 class="title mb-3">@lang('Update Your Profile')</h5>
                        <form action="" method="post" enctype="multipart/form-data" class="user-profile-form row">
                            @csrf
                            <input type='file' class="d-none" name="image" id="file-input" accept=".png, .jpg, .jpeg" />

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('First Name')</label>
                                    <input class="form--control" type="text" name="firstname" value="{{ $user->firstname }}" placeholder="@lang('Last Name')" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Last Name')</label>
                                    <input class="form--control" type="text" name="lastname" value="{{ $user->lastname }}" placeholder="@lang('Last Name')" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('State')</label>
                                    <input class="form--control" type="text" name="state" value="{{ @$user->state }}" placeholder="@lang('State')">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('City')</label>
                                    <input class="form--control" type="text" name="city" value="{{ @$user->city }}" placeholder="@lang('City')">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Zip')</label>
                                    <input class="form--control" type="text" name="zip" value="{{ @$user->zip }}" placeholder="@lang('Zip')">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Address')</label>
                                    <textarea class="form--control" name="address" rows="3">{{ @$user->address }}</textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn--base h-45 w-100">@lang('Update Profile')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $('select[name=country]').val("{{ @$user->address->country }}");

            $("#file-input").on('change', function() {
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result);
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        })(jQuery)
    </script>
@endpush
