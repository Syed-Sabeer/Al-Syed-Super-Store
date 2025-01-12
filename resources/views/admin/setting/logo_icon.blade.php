@extends('admin.layouts.app')
@section('panel')
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row gy-4">
            <div class="col-md-12">
                <div class="card bl--5 border--primary">
                    <div class="card-body">
                        <p class="text--primary">@lang('If the logo and favicon are not changed after you update from this page, please') <a href="{{ route('admin.system.optimize.clear') }}" class="text--info text-decoration-underline">@lang('clear the cache')</a> @lang('from your browser. As we keep the filename the same after the update, it may show the old image for the cache. usually, it works after clear the cache but if you still see the old logo or favicon, it may be caused by server level or network level caching. Please clear them too.')</p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label> @lang('Logo For White Background')</label>
                                <x-image-uploader name="logo_dark" :imagePath="siteLogo('dark') . '?' . time()" :size="false" class="w-100" id="uploadLogo" :required="false" />
                            </div>

                            <div class="form-group col-md-6">
                                <label> @lang('Logo For Dark Background')</label>
                                <x-image-uploader name="logo" :imagePath="siteLogo() . '?' . time()" :size="false" class="w-100" id="uploadLogo1" :required="false" :darkMode="true" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-4 flex-wrap">
                            <div class="form-group">
                                <label> @lang('Favicon')</label>
                                <x-image-uploader name="favicon" :imagePath="siteFavicon() . '?' . time()" :size="false" id="uploadFavicon" :required="false" />
                            </div>

                            <div class="form-group">
                                <label> @lang('Preloader')</label>
                                <x-image-uploader name="preloader" :imagePath="getImage(getFilePath('logoIcon') . '/preloader.gif') . '?' . time()" :size="false" id="preloader" :required="false" accept=".gif" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
            </div>
        </div>
    </form>
@endsection
