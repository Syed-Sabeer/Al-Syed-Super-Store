@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.shipping.methods.store', $shippingMethod->id ?? 0) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Name')</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="{{ @$shippingMethod->name }}" name="name" required />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Charge')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="number" name="charge" class="form-control" step="any" min="0" value="{{ isset($shippingMethod) ? getAmount($shippingMethod->charge) : '' }}" required />
                                    <span class="input-group-text" id="basic-addon2">{{ __(gs('cur_text')) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Delivered In')</label>
                            </div>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="number" class="form-control" min="0" value="{{ @$shippingMethod->shipping_time }}" name="deliver_in" />
                                    <span class="input-group-text" id="basic-addon2">@lang('Day')</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label>@lang('Description')</label>
                            </div>
                            <div class="col-md-10">
                                <textarea rows="5" class="form-control nicEdit" name="description">{{ @$shippingMethod->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center">
        <x-back route="{{ route('admin.shipping.methods.all') }}"></x-back>
    </div>
@endpush
