@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.promo.banner.save', @$banner->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Banner Type')</label>
                                    <select name="type" class="form-control" required>
                                        <option value="{{ Status::SINGLE_IMAGE_BANNER }}" @selected(@$banner->type == Status::SINGLE_IMAGE_BANNER)>@lang('Single Image Banner')</option>
                                        <option value="{{ Status::DOUBLE_IMAGE_BANNER }}" @selected(@$banner->type == Status::DOUBLE_IMAGE_BANNER)>@lang('Double Image Banner')</option>
                                        <option value="{{ Status::TRIPLE_IMAGE_BANNER }}" @selected(@$banner->type == Status::TRIPLE_IMAGE_BANNER)>@lang('Triple Image Banner')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', @$banner->title) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row image-wrapper">
                            @if (!isset($banner))
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>@lang('Image')</label>
                                        <x-image-uploader class="w-100" type="singlePromoBanner" name="images[0][image]" />
                                    </div>

                                    <div class="form-group">
                                        <label>@lang('Image Link')</label>
                                        <input type="text" class="form-control" name="images[0][link]">
                                    </div>
                                </div>
                            @else
                                @php
                                    $fileKey = $banner->fileKeyName();
                                    $class = $banner->type == Status::SINGLE_IMAGE_BANNER ? 'col-lg-12' : ($banner->type == Status::DOUBLE_IMAGE_BANNER ? 'col-lg-6' : 'col-lg-4');
                                @endphp

                                @foreach ($banner->images as $bannerImage)
                                    <div class="{{ $class }}">
                                        <div class="form-group">
                                            <label>{{ Number::ordinal($loop->iteration) }} @lang('Image')</label>
                                            <x-image-uploader class="w-100" type="{{ $fileKey }}" :image="$bannerImage->image" name="images[{{ $bannerImage->id }}][image]" :required="false" />
                                        </div>

                                        <div class="form-group">
                                            <label>{{ Number::ordinal($loop->iteration) }} @lang('Image Link')</label>
                                            <input type="text" class="form-control" name="images[{{ $bannerImage->id }}][link]" value="{{ $bannerImage->link }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.promo.banner.index') }}" />
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            const single = @json(Status::SINGLE_IMAGE_BANNER);
            const double = @json(Status::DOUBLE_IMAGE_BANNER);
            const triple = @json(Status::TRIPLE_IMAGE_BANNER);
            const wrapper = $('.image-wrapper');

            $('[name=type]').on('change', function() {
                let html = '';
                if ($(this).val() == single) {
                    html = `<div class="col-lg-12">
                                <div class="form-group">
                                    <label class="required">@lang('Image')</label>
                                    <x-image-uploader class="w-100" type="singlePromoBanner" name="images[0][image]"/>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Image Link')</label>
                                    <input type="text" class="form-control" name="images[0][link]">
                                </div>
                            </div>`;
                } else if ($(this).val() == double) {
                    html = `<div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required">@lang('1st Image')</label>
                                    <x-image-uploader class="w-100" type="doublePromoBanner" id="promo-banner-one" name="images[0][image]" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('1st Image Link')</label>
                                    <input type="text" class="form-control" name="images[0][link]">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required">@lang('2nd Image')</label>
                                    <x-image-uploader class="w-100" type="doublePromoBanner" id="promo-banner-two" name="images[1][image]" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('2nd Image Link')</label>
                                    <input type="text" class="form-control" name="images[1][link]">
                                </div>
                            </div>`;
                } else if ($(this).val() == triple) {
                    html = `<div class="col-lg-4">
                                <div class="form-group">
                                    <label class="required">@lang('1st Image')</label>
                                    <x-image-uploader class="w-100" type="triplePromoBanner" id="promo-banner-one" name="images[0][image]" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('1st Image Link')</label>
                                    <input type="text" class="form-control" name="images[0][link]">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="required">@lang('2nd Image')</label>
                                    <x-image-uploader class="w-100" type="triplePromoBanner" id="promo-banner-two" name="images[1][image]" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('2nd Image Link')</label>
                                    <input type="text" class="form-control" name="images[1][link]">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="required">@lang('3rd Image')</label>
                                    <x-image-uploader class="w-100" type="triplePromoBanner" id="promo-banner-three" name="images[2][image]" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('3rd Image Link')</label>
                                    <input type="text" class="form-control" name="images[2][link]">
                                </div>
                            </div>`;
                }

                wrapper.html(html);
            });
        })(jQuery);
    </script>
@endpush
