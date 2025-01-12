@extends('admin.layouts.app')
@section('panel')
    @php
        $collections = collect(getPageSections(true))->groupBy('page', true)->whereNull('parent')->sortKeysDesc();
    @endphp

    <div class="row gy-4">
        @foreach ($collections as $key => $item)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-3 justify-content-between align-items-center flex-wrap gap-2">
                            <h4>{{ keyToTitle($key ? $key . ' Page Contents' : 'Other Contents') }}</h4>
                        </div>
                        <div class="row gy-4">
                            @foreach ($item as $k => $secs)
                                @if ($secs['builder'] && !@$secs['hide_builder'])
                                    <div class="col-md-3">
                                        <div class="frontend-section-card">
                                            <h6>{{ __($secs['name']) }}</h6>
                                            <a href="{{ route('admin.frontend.sections', $k) }}" class="btn btn--light btn-sm"><i class="las la-cog me-0"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if ($key == 'home')
                                <div class="col-md-3">
                                    <div class="frontend-section-card">
                                        <h6>@lang('Promo Banners')</h6>
                                        <a href="{{ route('admin.promo.banner.index') }}" class="btn btn--light btn-sm"><i class="las la-cog me-0"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="frontend-section-card">
                                        <h6>@lang('Product Collections')</h6>
                                        <a href="{{ route('admin.collection.index') }}" class="btn btn--light btn-sm"><i class="las la-cog me-0"></i></a>
                                    </div>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@push('style')
    <style>
        .frontend-section-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #ededed;
            padding: 15px;
            border-radius: 5px;
            background: #fff;
            transition: all .2s;
        }

        .frontend-section-card:hover {
            background: #e7e7e7;
        }

        .system-search-icon {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            aspect-ratio: 1;
            padding: 5px;
            display: grid;
            place-items: center;
            color: #888;
        }

        .searchInput {
            border: 1px solid #ededed;
        }

        .system-search-icon~.form-control {
            padding-left: 45px;
        }
    </style>
@endpush
