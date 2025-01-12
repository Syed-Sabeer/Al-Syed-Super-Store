@props(['products' => collect([]), 'url'])

<div class="products-container existing">
    @if (!blank($products))
        @foreach ($products as $product)
            <div class="product-item">
                <input type="hidden" name="products[]" value="{{ $product->id }}">
                <img src="{{ $product->mainImage() }}" alt="{{ $product->name }}" width="50" class="fit-image">
                <span>{{ $product->name }}</span>

                <button type="button" class="remove-btn">
                    <la class="la la-times"></la>
                </button>
            </div>
        @endforeach
    @endif
</div>

@push('modal')
    <div class="modal fade" id="productUploaderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <form id="productSearchForm" class="input-group">
                        <input type="text" class="form-control search-input" placeholder="@lang('Search Product')">
                        <button type="submit" class="input-group-text text-muted text-sm">@lang('Apply Search')</button>
                    </form>
                </div>
                <div class="modal-body">
                    <div class="preloader">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <i class="fa fa-circle-notch fa-spin text-muted"></i>
                        </div>
                    </div>
                    <div class="products-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="button" class="btn btn--primary addProductToCollection">@lang('Apply Selected')</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            let selectedProducts = new Set();
            const defaultImage = `{{ getImage(null) }}`;

            const loadMoreBtn = () => {
                return `<div class="d-flex justify-content-center mt-3"><a href="#" class="loadMoreBtn">@lang('Load More')</a></div>`;
            };

            const handleLoadMoreBtnClick = (e) => {
                e.preventDefault();
                loadProducts(e.target.href);
            };

            const loadProducts = async (url = `{{ $url }}`) => {
                const parentContainer = $('#productUploaderModal .modal-body');
                parentContainer.find('.preloader').show();

                const existingProductIds = new Set($('.products-container.existing input[name="products[]"]').map(function() {
                    return $(this).val();
                }).get());

                $.get(url).done((response) => {

                    parentContainer.find('.preloader').hide();
                    const container = $('#productUploaderModal .products-container');
                    if (response.data) {
                        let content = '';
                        response.data.forEach(product => {
                            if (!existingProductIds.has(String(product.id))) {
                                content += `
                                    <div class="product-item" data-id="${product.id}">
                                        <input type="hidden" value="${product.id}">
                                        <img src="${product.display_image?.thumb_url ?? defaultImage}" alt="product-image" width="50">
                                        <span>${product.name}</span>
                                    </div>
                                `;
                            }
                        });

                        const loadMoreButton = parentContainer.find('.loadMoreBtn');
                        if (response.next_page_url) {
                            if (!loadMoreButton.length) {
                                parentContainer.append(loadMoreBtn());
                            }
                            parentContainer.find('.loadMoreBtn').attr('href', response.next_page_url);
                        } else {
                            $(loadMoreButton).remove();
                        }

                        $(content).appendTo(container);
                    }
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error('Error fetching media files:', textStatus, errorThrown);
                });
            };

            const handleProductSearch = () => {
                const keyword = $('#productUploaderModal .search-input').val();
                const url = `{{ route('admin.collection.products') }}?search=${encodeURIComponent(keyword)}`;

                $('#productUploaderModal .products-container').empty();
                loadProducts(url);
            };

            const addProductClickHandler = () => {
                const container = $('#productUploaderModal .products-container');
                container.empty();
                loadProducts();
                $('#productUploaderModal').modal('show');
            }

            $('.addMoreProduct').on('click', addProductClickHandler);

            $('.addProductToCollection').on('click', function() {
                const container = $('.products-container.existing');

                selectedProducts.forEach(productId => {
                    if (container.find(`input[value="${productId}"]`).length === 0) {
                        const productElement = $(`.product-item[data-id="${productId}"]`);
                        const productHtml = `
                    <div class="product-item">
                        <input type="hidden" name="products[]" value="${productId}">
                        ${productElement.html()}
                        <button type="button" class="remove-btn"><la class="la la-times"></la></button>
                    </div>
                `;
                        container.append(productHtml);
                    }
                });

                $('#productUploaderModal').modal('hide');
                selectedProducts.clear();
            });

            $('#productUploaderModal').on('click', '.product-item', function() {
                const productId = $(this).data('id');
                if (selectedProducts.has(productId)) {
                    selectedProducts.delete(productId);
                    $(this).removeClass('selected');
                } else {
                    selectedProducts.add(productId);
                    $(this).addClass('selected');
                }
            });

            $(document).on('click', '.product-item .remove-btn', function() {
                const productId = $(this).closest('.product-item').find('input').val();
                $(this).closest('.product-item').remove();
                selectedProducts.delete(productId);
            });

            $('#productSearchForm').on('submit', (e) => {
                e.preventDefault();
                handleProductSearch();
            });
            $(document).on('click', '.loadMoreBtn', handleLoadMoreBtnClick);
        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .product-item .remove-btn {
            background: transparent;
            border: none;
            color: #3c3c3c;
            font-size: 16px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: auto;
        }

        .loadMoreBtn {
            background: #f7f7f7;
            color: #222;
            padding: 8px 16px;
            border-radius: 5px;
            border: 1px solid #ebebeb;
        }

        .loadMoreBtn:hover {
            background: #f1f1f1;
            color: #222;
        }


        .products-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-search-input {
            background: #fff !important;
            position: sticky;
            top: 0;
        }

        .product-item {
            border: 1px solid #ebebeb;
            padding: 10px;
            border-radius: 4px;
            display: flex;
            gap: 10px;
            align-items: center;
            position: relative;
        }

        .modal .product-item {
            cursor: pointer;
        }

        .modal-body .preloader {
            height: 200px;
        }

        .product-item.selected {
            background-color: #f7f7f7;
        }

        .products-container.existing {
            max-height: calc(100vh - 365px);
            overflow-y: auto;
        }
    </style>
@endpush
