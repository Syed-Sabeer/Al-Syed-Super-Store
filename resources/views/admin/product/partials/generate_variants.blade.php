<div class="card">
    <div class="card-body">
        <p class="mb-2 text-muted"> <i class="la la-info-circle"></i> @lang('Variations for this product have not been created based on the current combination of attributes and attribute values. By clicking on the Generate Variations button you are going to create all possible variations.')</p>
        <button type="button" class="btn btn--primary" id="generateVariantBtn">@lang('Generate Variants')</button>
    </div>
</div>
@push('script')
    <script>
        (function($) {
            "use strict";

            const handleVariantGeneration = (response) => {
                notify(response.status, response.message);

                if (response.status == 'success') {
                    window.location.reload();
                }
            }

            const generateVariants = () => {
                $.ajax({
                    url: `{{ route('admin.products.variants.generate', $product->id) }}`,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: handleVariantGeneration
                });
            }

            $('#generateVariantBtn').on('click', generateVariants);
        })(jQuery);
    </script>
@endpush
