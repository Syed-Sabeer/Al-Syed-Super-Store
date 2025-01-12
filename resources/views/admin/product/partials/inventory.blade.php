<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h5 class="card-title mb-0">@lang('Inventory')</h5>
    </div>

    <div class="card-body">

        <div class="form-group row sku-wrapper">
            <div class="col-xl-4 col-md-3">
                <label>@lang('Product SKU')</label>
            </div>
            <div class="col-xl-8 col-md-9">
                <input type="text" name="sku" class="form-control sku-field" value="{{ old('sku', @$product->sku) }}" />
            </div>
        </div>

        <div class="form-group row">

            <div class="col-xl-4 col-md-3">
                <label>@lang('Track Inventory') <i class="la la-info-circle text-muted" title="@lang('If Track Inventory is enabled, the user cannot place an order if the item is unavailable.')"></i></label>
            </div>
            <div class="col-xl-8 col-md-9">
                <x-toggle-switch class="track_inventory-field" name="track_inventory" value="1" :checked="@$product->track_inventory" />
            </div>
        </div>

        <div class="form-group show-stock-wrapper @if (@$product->track_inventory != 1) d-none @endif row">
            <div class="col-xl-4 col-md-3">
                <label>@lang('Show Stock Quantity') <i class="la la-info-circle text-muted" title="@lang('The stock quantity will be shown to the user if enabled')"></i></label>
            </div>

            <div class="col-xl-8 col-md-9">
                <x-toggle-switch name="show_stock" class="show-stock-field" value="1" :checked="@$product->show_stock" />
            </div>
        </div>

        <div class="form-group row" id="stockQuantityWrapper">
            <div class="col-xl-4 col-md-3">
                <label>@lang('Stock Quantity')</label>
            </div>

            <div class="col-xl-8 col-md-9">
                <input type="number" name="in_stock" class="form-control in_stock-field" value="{{ old('in_stock', @$product->in_stock) }}" />
                @if (@$product->track_inventory)
                    <a href="{{ route('admin.products.stock.log', $product->id) }}" class="text-muted text-decoration-underline">@lang('View Stock Log')</a>
                @endif
            </div>
        </div>

        <div class="form-group row" id="alertQuantityWrapper">
            <div class="col-xl-4 col-md-3">
                <label>@lang('Alert Quantity') <i class="la la-info-circle text-muted" title="@lang('If the product has reached its alert quantity, it is visible to the admin')"></i></label>
            </div>

            <div class="col-xl-8 col-md-9">
                <input type="number" min="0" name="alert_quantity" class="form-control alert_quantity-field" value="{{ old('alert_quantity', @$product->alert_quantity) }}" />
            </div>
        </div>
    </div>
</div>
