<div class="card mt-3">
    <div class="card-body">
        <label for="categories">@lang('Categories')</label>
        @if (!blank($categories))
            <input class="form-control mb-3 searchCategory" placeholder="Search Category" />

            <div class="categories-checkboxes">
                <x-category-checkbox :categories="$categories" />
            </div>
        @else
            <div class="h-100 py-3 d-flex justify-content-center align-items-center flex-column">
                <small class="text-muted">@lang('No category added yet')</small>
                <a href="{{ route('admin.category.all') }}">@lang('Add Categories From Here')</a>
            </div>
        @endif

        <div class="not-found d-none">
            <div class="empty-notification-list text-center">
                <img src="{{ getImage('assets/images/empty_list.png') }}" alt="empty">
                <p class="message">@lang('No categories found')</p>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('assets/admin/js/highlighter22.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            const categories = @json(@$product ? $product->categories->pluck('id') : []);

            $('.searchCategory').on('input', function() {
                const searchTerm = $(this).val().trim().toLowerCase();

                $('.child').addClass('d-none');
                $('.not-found').addClass('d-none');
                $('.categories-checkboxes').removeClass('d-none');

                let found = false;

                $('.form-check-label').each(function() {
                    const labelText = $(this).text().trim().toLowerCase();

                    if (labelText.includes(searchTerm)) {
                        $(this).closest('.child').removeClass('d-none');
                        $(this).closest('.child').parents('.child').removeClass('d-none');
                        found = true;
                    }
                });

                if (!found) {
                    $('.not-found').removeClass('d-none');
                    $('.categories-checkboxes').addClass('d-none');
                }
            });

            $('.searchCategory').highlighter22({
                targets: [".categories-checkboxes .form-check-label span", ],
            });

            @if (@$product)
                $(`[name="categories[]"]`).val(categories);
                const firstChecked = document.querySelector('.categories-checkboxes input[type="checkbox"]:checked');
                if (firstChecked) {
                    const container = document.querySelector('.categories-checkboxes');
                    const containerTop = container.getBoundingClientRect().top;
                    const itemTop = firstChecked.getBoundingClientRect().top;

                    const scrollToPosition = container.scrollTop + (itemTop - containerTop - 15);

                    container.scrollTo({
                        top: scrollToPosition
                    });
                }
            @endif
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .not-found,
        .categories-checkboxes {
            height: 295px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 1rem 0;
        }

        .not-found {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .not-found .message {
            color: #b8b8b8
        }
    </style>
@endpush
