@extends('admin.layouts.app')

@section('panel')
    <form action="{{ route('admin.collection.save', @$collection->id ?? 0) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" class="form-control" name="title" value="{{ @$collection->title ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <label>@lang('Banner')</label>
                                @if (@$collection && $collection->banner)
                                    <a href="javascript:void(0)" data-question="@lang('Are you sure to delete this banner?')" data-action="{{ route('admin.collection.banner.delete', @$collection->id) }}" class="text--danger confirmationBtn">@lang('Delete Banner')</a>
                                @endif
                            </div>
                            <x-image-uploader class="w-100" type="collection" :image="@$collection->banner" id="promo-banner-three" name="banner" :required="false" />
                        </div>

                         <div class="form-group">
                            <label>@lang('Banner Position')</label>
                            <select name="banner_position" class="form-control">
                                <option value="left" @selected(@$collection->banner_position == 'left')>@lang('Left')</option>
                                <option value="right" @selected(@$collection->banner_position == 'right')>@lang('Right')</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <h5>@lang('Products')</h5>
                            <button type="button" class="btn btn--primary addMoreProduct"><i class="la la-plus"></i>@lang('Add Products')</button>
                        </div>
                        <x-product-picker :products="@$collection?->products()" :url="route('admin.collection.products')" />
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn--primary w-100 h-45 mt-4">@lang('Submit')</button>
    </form>

    <x-confirmation-modal />
    @stack('modal')
@endsection


@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.collection.index') }}" />
@endpush


<!-- Modal -->
