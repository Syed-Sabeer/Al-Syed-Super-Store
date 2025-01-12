(function ($) {
    'use strict';

    $.fn.productDetails = function ({ productId, totalAttributes, stockQuantity, showStockQuantity, trackInventory, variantImageLoadUrl, checkStockUrl }) {

        const addToCartButton = $(this).find($(".addToCart"));
        
        var initialized = false;

        function init() {
            if (initialized) return;
            setStockBadge(trackInventory, stockQuantity, showStockQuantity);
            addEventListeners();
            initialized = true;
        }

        function setSku(sku) {
            $('#productSku').text(sku);
        }

        function setProductPrice(priceContent) {
            $('#productPrice').html(priceContent);
        }

        function setStockBadge(trackInventory, stockQuantity = 'INFINITY', showStockQuantity = false) {
            var stockBadge = $('#stockBadge');

            stockBadge.removeClass('badge badge--success badge--danger').empty();

            if(!trackInventory) {
                stockBadge.hide();
                return;
            }

            let content;
            let badge;

            if (stockQuantity > 1) {
                content = showStockQuantity ? `In Stock: ${stockQuantity}` : 'Available in Stock';
                badge = 'badge badge--success';
            } else {
                content = `Out of Stock`;
                badge = 'badge badge--danger';
            }
            
            stockBadge.text(content).addClass(badge);
            stockBadge.show();
        }

        function checkStockAvailability(attributeArray) {
            return $.get(checkStockUrl, {
                variant: attributeArray.sort()
            });
        }

        function loadVariantImages(attributeArray, productId) {
            const url = variantImageLoadUrl.replace(':productId', productId);
            const previousMediaIds = [];

            $(document).find(`.media-item-nav`).each((index, element)=>{
                previousMediaIds.push($(element).data('media_id'));
            });

            $.get(url, { attribute_values: JSON.stringify(attributeArray) }).done((response) => {
                if (!response.error) {
                    if(JSON.stringify(response.media_ids) != JSON.stringify(previousMediaIds)){
                        $('#variantImages').html(response.images);
                        initializeXZOOM();
                        initImgSlider();
                        let activeElements = $('.attributeBtn.active');
                        let filteredElements = activeElements.filter(function () {
                            return $(this).data('media_id') > 0;
                        });
    
                        $(document).find(`.media-item-nav[data-media_id=${filteredElements.data('media_id') }]`).click();
                    }
                }
            });
        }

        function attributeClickHandler(e) {
            const button = $(e.currentTarget);
            const attributes = [];
            
            const mediaId = button.data('media_id');
            
            if(mediaId) {
                $(`.media-item-nav[data-media_id=${mediaId}]`).click();
            }
            
            // Toggle active class
            button.closest('.attributeValueArea').find('.attributeBtn').removeClass('active');
            button.addClass('active');
            
            const activeAttributes = $(document).find('.attributeBtn.active');
            
            // Check if all attributes are selected
            if (activeAttributes.length === (totalAttributes*1)) {
                
                $('.product-attribute .ajax-preloader').removeClass('d-none');

                const attributeArray = activeAttributes.map(function (i, element) {
                    return $(element).data('attribute').id;
                }).get();

                loadVariantImages(attributeArray, productId);

                activeAttributes.each(function (_, attr) {
                    attributes.push($(attr).data('attribute').id.toString());
                });

                checkStockAvailability(attributes)
                .done((response) => {

                    if(response.message) {
                        notify(response.status, response.message);
                    }
                    
                    if(response.status == 'error') {
                        addToCartButton.attr('disabled', true);
                        setStockBadge(true, 0);
                        setSku(response.sku??'Not Available');
                    }else{
                        setProductPrice(response.formatted_price);
                        if(!response.track_inventory){
                            setStockBadge(response.track_inventory);
                            addToCartButton.attr('disabled', false);
                        }else{
                            setStockBadge(response.track_inventory, response.stock_quantity, response.show_stock);
                            addToCartButton.attr('disabled', response.price == null);
                        }
                    }
                    $('.product-attribute .ajax-preloader').addClass('d-none');
                });
            }
        }

        function addEventListeners() {
            $(document).off('click', '.attributeBtn').on('click', '.attributeBtn', (e) => attributeClickHandler(e));
            $(document).off('click', '.addToCart').on('click', '.addToCart', addToCartClickHandler);
        }

        init();
        return this;
    };
})(jQuery);
