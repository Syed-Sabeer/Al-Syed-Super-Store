<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('Product Description')</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Description')</label>
            </div>
            <div class="col-md-9">
                <textarea rows="5" class="form-control description-field" name="description" id="productDescription">@php echo ($product->description)??'' @endphp</textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Summary')</label>
            </div>
            <div class="col-md-9">
                <textarea rows="5" class="form-control" name="summary">{{ $product->summary ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>
