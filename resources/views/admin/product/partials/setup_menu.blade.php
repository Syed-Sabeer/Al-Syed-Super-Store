<div class="nav flex-md-column gap-2 nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <a class="nav-link active jsTriggeredTab" id="general-tab" data-bs-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">@lang('General')</a>

    <a class="nav-link jsTriggeredTab" id="media-content-tab" data-bs-toggle="pill" href="#media-content" role="tab" aria-controls="media-content" aria-selected="false">@lang('Media Contents')</a>

    <a class="nav-link jsTriggeredTab" id="description-tab" data-bs-toggle="pill" href="#description" role="tab" aria-controls="description" aria-selected="false">@lang('Description')</a>

    <a class="nav-link jsTriggeredTab" id="seo-tab" data-bs-toggle="pill" href="#seo" role="tab" aria-controls="seo" aria-selected="false">@lang('Seo Content')</a>

    <a class="nav-link jsTriggeredTab" id="inventory-tab" data-bs-toggle="pill" href="#inventory" role="tab" aria-controls="inventory" aria-selected="false">@lang('Inventory')</a>

    @if (@$product && $product->product_type == Status::PRODUCT_TYPE_VARIABLE)
        <a class="nav-link jsTriggeredTab" id="variants-tab" href="#variants" data-bs-toggle="pill" role="tab" aria-controls="variants" aria-selected="false">@lang('Variants')</a>
    @endif


    @isset($product)
        <a class="nav-link jsTriggeredTab" id="specifications-tab" href="#specifications" data-bs-toggle="pill" role="tab" aria-controls="specifications" aria-selected="false">@lang('Specification')</a>
    @endisset

    <a class="nav-link jsTriggeredTab" id="extra-description-tab" data-bs-toggle="pill" href="#extra-description" role="tab" aria-controls="extra-description" aria-selected="false">@lang('Extra Description')</a>

    <a class="nav-link jsTriggeredTab" id="downloadable-config-tab" data-bs-toggle="pill" href="#downloadable-config" role="tab" aria-controls="downloadable-config" aria-selected="false">@lang('Downloadable')</a>
</div>


@push('style')
    <style>
        .nav-pills-custom .nav-link {
            color: #555555;
            background: #fff;
            position: relative;
            font-size: .875rem;
            font-weight: 500;
            flex-grow: 1;
        }

        .nav-pills-custom .nav-link.active {
            color: #4634ff;
            background: #fff;
        }

        .nav-pills-custom .nav-link.active::before {
            opacity: 1;
        }

        .extra {
            border: 1px solid #ebebeb;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {

            const url = "{{ @$product ? route('admin.products.edit', @$product->id) : route('admin.products.create') }}";

            $('.jsTriggeredTab').on('click', function() {
                let tab = $(this).attr('href');
                window.location.href = url + tab;
                window.history.pushState(null, null, url + tab);
            });

        })(jQuery);
    </script>
@endpush
