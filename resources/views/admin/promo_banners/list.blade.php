@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card bg--light">
                <div class="card-body">
                    <small class="text-muted">
                        <i class="la la-info-circle"></i> @lang('Each group of promo banners will be treated as a single section and displayed on the website\'s home page. To enable these sections, add them from the') <a href="{{ route('admin.frontend.manage.section', 1) }}">@lang('Home Page Sections')</a>.
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive-md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Images')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">

                                @forelse($banners as $banner)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($banner->images as $bannerImage)
                                                    <div class="banner-image">
                                                        <a href="{{ $bannerImage->getImage() }}" class="image-popup">
                                                            <img src="{{ $bannerImage->getImage() }}" alt="">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                @if (Route::is('admin.promo.banner.trashed'))
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to restore this promotional banner?')" data-action="{{ route('admin.promo.banner.restore', $banner->id) }}"><i class="las la-trash-restore"></i>@lang('Restore')</button>
                                                @else
                                                    <a href="{{ route('admin.promo.banner.update', $banner->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-pencil-alt"></i>@lang('Edit')</a>

                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this promotional banner?')" data-action="{{ route('admin.promo.banner.delete', $banner->id) }}"><i class="las la-trash-alt"></i>@lang('Delete')</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($banners->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($banners) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.promo.banner.add') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>
    <x-back :route="route('admin.frontend.index')"/>
@endpush

@push('style')
    <style>
        .banner-image {
            width: 100px;
        }
    </style>
@endpush
