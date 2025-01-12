<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('SEO Contents')</h5>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Meta Title')</label>
            </div>
            <div class="col-md-9">
                <input type="text" class="form-control meta-title-field" name="meta_title" value="{{ isset($product) ? $product->meta_title : old('meta_title') }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Meta Description')</label>
            </div>
            <div class="col-md-9">
                <textarea name="meta_description" rows="5" class="form-control meta_description-field">{{ isset($product) ? $product->meta_description : old('meta_description') }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Meta Keywords')</label>
            </div>
            <div class="col-md-9">
                <select name="meta_keywords[]" class="form-control select2-auto-tokenize meta_keywords-field" multiple="multiple">
                    @if (@$product->meta_keywords)
                        @foreach ($product->meta_keywords as $option)
                            <option value="{{ $option }}" selected>{{ __($option) }}</option>
                        @endforeach
                    @endif
                </select>
                <small class="form-text text-muted">
                    <i class="las la-info-circle"></i>
                    @lang('Type , as separator or hit enter among keywords')
                </small>
            </div>
        </div>
    </div>
</div>
