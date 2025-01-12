<div class="card">
    <div class="card-header">
        <h6 class="card-title mb-0">@lang('Product Status')</h6>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-xl-12">
                <label>@lang('Publish Product')</label>
            </div>
            <div class="col-xl-12">
                <x-toggle-switch name="is_published" value="1" :checked="@$product->is_published == Status::YES" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-xl-12">
                <label>
                    @lang('Show in Products Page')
                </label>
            </div>
            <div class="col-xl-12">
                <x-toggle-switch name="is_showable" value="1" :checked="@$product->is_showable" />
            </div>
        </div>
    </div>
</div>
