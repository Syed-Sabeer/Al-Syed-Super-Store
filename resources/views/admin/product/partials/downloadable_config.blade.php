<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('Downloadable Configuration')</h5>
    </div>
    <div class="card-body">

        <div class="form-group row align-items-center">
            <div class="col-xl-4 col-md-3">
                <label>@lang('Downloadable Product')</label>
            </div>

            <div class="col-xl-8 col-md-9">
                <x-toggle-switch class="is_downloadable-field" name="is_downloadable" value="1" :checked="@$product->is_downloadable == 1" />
            </div>
        </div>

        <div class="form-group row d-none" id="deliverTypeWrapper">
            <div class="col-xl-4 col-md-3">
                <label>@lang('Delivery Type')</label>
            </div>

            <div class="col-xl-8 col-md-9">
                <select class="form-control delivery_type-field" name="delivery_type">
                    <option value="" hidden>@lang('Select One')</option>
                    <option value="{{ Status::DOWNLOAD_INSTANT }}" @selected(@$product->delivery_type == Status::DOWNLOAD_INSTANT)>@lang('Instant Download')</option>
                    <option value="{{ Status::DOWNLOAD_AFTER_SALE }}" @selected(@$product->delivery_type == Status::DOWNLOAD_AFTER_SALE)>@lang('After Sale')</option>
                </select>
            </div>
        </div>

        <div class="form-group row d-none" id="digitalFileWrapper">
            <div class="col-xl-4 col-md-3">
                <label class="required">@lang('File')</label>
            </div>

            <div class="col-xl-8 col-md-9">
                <input type="file" class="form-control file-field" name="file" accept=".zip" />
                @if (@$product->digitalFile)
                    <div class="text-end">
                        <a href="{{ route('admin.products.digital.download', encrypt($product->digitalFile->id)) }}">@lang('View File')</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
