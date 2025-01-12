<script>
    'use strict';
    const cartCountElement = $('.cartItemCount');
    const quickViewModal = $('#quickView');

    const getCartCount = () => $.get("{{ route('cart.items.count') }}");
    const getCartSubtotal = () => $.get("{{ route('cart.items.subtotal') }}");
    const getCartData = () => $.get("{{ route('cart.shortlist') }}");

    const absoluteValue = (amount) => {
        return Math.abs(parseFloat(amount).toFixed(2));
    }

    const setCartCount = (count = null) => {

        console.log(count);

        if (count === null) {
            getCartCount().then((response) => setCartCount(response));
        }

        if (count > 0) {
            cartCountElement.text(count).removeClass('d-none');
        } else {
            cartCountElement.text(0).addClass('d-none');
        }
    }

    const setCartSubtotal = (amount = null) => {
        if (amount === null) {
            getCartSubtotal().then((response) => updateSubtotal(response));
        }
        amount = absoluteValue(amount ?? 0);
        updateSubtotal(amount);
    }

    const updateSubtotal = function(amount) {
        $('.cartSubtotal').text(amount);
    }

    const setPartialCart = (cartData) => {
        if (!cartData) {
            getCartData().then((response) => setPartialCart(response));
        }

        $('.cart--products').html(cartData);
        lazyload();
    }

    const getCompareData = () => {
        $.get("{{ route('compare.count') }}").done((response) => {
            if (response.total) {
                $('.compare-count').text(response.total);
                $('.compare-count').removeClass('d-none');
            } else {
                $('.compare-count').addClass('d-none');
            }
        });
    }

    const getWishlistData = () => {
        $.get("{{ route('wishlist.shortlist') }}").done((response) => {

            $('.wish--products').html(response);
            lazyload();
        });
    }

    const getWishlistTotal = () => {
        $.get("{{ route('wishlist.items.count') }}").done((response) => {
            if (response > 0) {
                $('.wishlist-count').text(response).removeClass('d-none');
            } else {
                $('.wishlist-count').addClass('d-none');
            }
        });
    }

    const removeFromCartHandler = function() {
        const couponAmount = Number($('#couponAmount').text());

        if (couponAmount > 0) {
            notify('info', 'Applied coupon has been removed');
            $('.couponContent').hide();
            $('#couponAmount').text(0);
        }

        const parent = $(this).parents('.cartItem');
        const subTotal = Number($('.cartSubtotal').first().text());
        const thisPrice = Number(parent.find('.totalPrice').text());

        const sidebarCart = $(this).parents('.cart--products');

        const data = {
            _token: `{{ csrf_token() }}`
        };

        const action = `{{ route('cart.remove', '') }}/${$(this).data('id')}`;

        $.post(action, data,
            function(response) {
                if (response.status == 'success') {
                    parent.remove();

                    if (thisPrice) {
                        $('.cartSubtotal').text((subTotal - thisPrice).toFixed(2));
                        $('#finalTotal').text((subTotal - thisPrice).toFixed(2));
                    }

                    if ($(".cartItem").length == 0) {
                        if (sidebarCart.length == 0) {
                            $(".cart-container").html(`
                            <div class="single-product-item no_data empty-cart__page">
                                <div class="no_data-thumb text-center mb-4">
                                    <img src="{{ getImage('assets/images/empty_cart.png') }}" alt="Empty Cart">
                                </div>
                                <h6>@lang('Your cart is empty')</h6>

                                <a href="{{ route('home') }}" class="btn btn-outline--light">@lang('Browse Products')</a>
                            </div>
                            `);

                            $('.cart-next-step').remove();
                        } else {
                            sidebarCart.html(`
                            <div class="single-product-item no_data">
                                <div class="no_data-thumb">
                                    <img src="{{ getImage('assets/images/empty_cart.png') }}" alt="@lang('Empty Cart')">
                                </div>
                                <h6>@lang('Your cart is empty')</h6>
                            </div>
                            `);
                        }
                    }

                    setCartCount(response.cartItemCount);
                } else {
                    notify(response.status, response.message);
                }
            }
        );
    }


    const updateCart = (quantity, cartItem) => {
        let couponAmount = Number($('#couponAmount').text());
        if (couponAmount > 0) {
            notify('info', 'Applied coupon has been removed');
            $('#couponAmount').text(0)
            $('.couponContent').hide();
        }


        let unitPrice = Number(cartItem.data().price);

        const data = {
            _token: `{{ csrf_token() }}`,
            quantity: quantity
        }

        $.post(`{{ route('cart.update', '') }}/${cartItem.data().id}`, data,
            function(response) {
                if (response.status === 'error') {
                    cartItem.find('[name=quantity]').val(response.quantity);
                    notify('error', response.message);
                } else {

                    cartItem.find('.totalPrice').text(absoluteValue(quantity * unitPrice));

                    let priceArray = $('.cartItem .totalPrice').map((i, element) => $(element).text()).get();
                    var subtotal = priceArray.reduce((acc, element) => acc + Number(element), 0);


                    $('.cartSubtotal').text(absoluteValue(subtotal));

                    var finalAmount = subtotal - absoluteValue($('#couponAmount').text());

                    $('#finalTotal').text(absoluteValue(finalAmount));

                    if (quantity > 1) {
                        cartItem.find('.qtyButton.minus').removeAttr('disabled');
                    } else {
                        cartItem.find('.qtyButton.minus').attr('disabled', true);
                    }
                }
            }
        );
    }

    const quantityButtonClickHandler = function() {
        const parent = $(this).parent();
        let qty = parent.find("input").val() * 1;
        qty = $(this).hasClass('plus') ? ++qty : --qty;
        qty = qty < 1 ? 1 : qty;

        parent.find("input").val(qty);

        if ($(this).parent().data('update') != 'no') {
            updateCart(qty, $(this).parents('.cartItem'));
        }

        if (qty > 1) {
            parent.find(".minus").removeAttr('disabled');
        } else {
            parent.find(".minus").attr('disabled', true);
        }
    }

    const applyCouponClickHandler = () => {
        var code = $('input[name=coupon_code]').val();
        var subtotal = absoluteValue($('.cartSubtotal').first().text());

        if (!code) {
            notify('error', 'Coupon field is required');
            return false;
        }

        const data = {
            _token: `{{ csrf_token() }}`,
            code: code,
            subtotal: subtotal,
            categories: @json(@$productCategories),
        }

        $.post("{{ route('cart.coupon.apply') }}", data).done((response) => {
            notify(response.status, response.message);
            if (response.status == 'success') {
                let couponAmount = response.amount;
                if (couponAmount > subtotal) {
                    couponAmount = subtotal;
                }

                $('#couponAmount').text(couponAmount);
                $('.couponCode').text(response.coupon_code);
                $('#finalTotal').text(absoluteValue((subtotal - couponAmount)));
                $('[name=coupon_code]').val('');
                $('.couponContent').removeClass('d-none').hide().show('300');
                $('#applyCoupon').attr('disabled', true);
            }
        });
    }

    const couponCodeKeyHandler = (event) => {
        if (event.keyCode == 13) {
            applyCouponClickHandler();
        }
    }

    const removeCouponClickHandler = () => {
        $.post(`{{ route('cart.coupon.remove') }}`, {
            _token: `{{ csrf_token() }}`
        }).done((response) => {
            if (response.status == 'success') {
                notify('success', response.success);
                $('#finalTotal').text(absoluteValue($('.cartSubtotal').first().text()));
                $('.couponContent').hide('slow');
                $('input[name=coupon_code]').val('');
                $('#applyCoupon').removeAttr('disabled');
            }
        });
    }

    const addToWishlistClickHandler = function() {
        const button = $(this);
        const productId = $(this).data('id');

        let products = $(`.addToWishlist[data-id="${productId}"]`);

        const data = {
            _token: `{{ csrf_token() }}`
        }

        $.post(`{{ route('wishlist.add', '') }}/${productId}`, data).done((response) => {
            if (response.status === 'success') {
                getWishlistData();
                getWishlistTotal();
                $.each(products, function(i, product) {
                    $(product).toggleClass('active');
                });
            }
            notify(response.status, response.message);
        });

    }

    const removeFromWishlistClickHandler = function(e) {

        const id = $(this).data('id');
        const productId = $(this).data('pid');
        let page = $(this).data('page');
        let parent = $(this).closest('.wishlistItem');

        const data = {
            _token: `{{ csrf_token() }}`
        }

        $.post(`{{ route('wishlist.remove', '') }}/${id}`, data).done((response) => {
            if (response.status === 'success') {
                getWishlistData();
                getWishlistTotal();


            } else {
                notify(response.status, response.message);
            }

            if (productId) {
                let products = $(`.addToWishlist[data-id="${productId}"]`);
                $.each(products, function(i, v) {

                    if ($(v).parents('.product-wishlist').find('.wishlist-label').length) {
                        $(v).parents('.product-wishlist').find('.wishlist-label').text("@lang('Add to Wishlist')")
                    }

                    if ($(v).hasClass('active')) {
                        $(v).removeClass('active');
                    }
                });
            }
            if (page == 1) {
                if (id == 0) {
                    $('.wishlist-row').html(`<x-dynamic-component :component="frontendComponent('empty-message')" />`);
                    $('.removeAllBtn').remove();
                    lazyload();
                } else {
                    parent.hide(300);
                }
            }
        });
    }

    const addToCompareClickHandler = function() {
        let productId = $(this).data('id');
        let products = $(`.addToCompare[data-id="${productId}"]`);

        $.ajax({
            url: "{{ route('compare.add') }}",
            method: "POST",
            data: {
                product_id: productId,
                _token: `{{ csrf_token() }}`
            },
            success: function(response) {
                if (response.success) {
                    getCompareData();


                    $.each(products, function(i, product) {
                        $(product).toggleClass('active');
                    });

                    notify('success', response.success);
                } else {
                    notify('error', response.error);
                }
            }
        });
    }

    const quickViewClickHandler = function() {
        $.get(`{{ route('product.detail', '') }}/${$(this).data('product')}`).done((response) => {
            quickViewModal.find('.modal-body').html(response);
            setBackgroundImage();
            initImgSlider();
            initializeXZOOM();
        });
        quickViewModal.modal('show');
    }

    const loadReviews = (url) => {

        $.get(url).done((response) => {
            $('#loadMoreBtn').remove();
            $('.review-area').append(response);
            lazyload();
        });
    }

    const loadMoreClickHandler = function() {
        $('#loadMoreBtn').html(`<i class="fa fa-spinner fa-spin"></i> @lang('Loading Reviews')`);
        loadReviews($(this).data('url'));
    }


    const quantityChangeHandler = function() {
        const parent = $(this).parent();
        let quantity = $(this).val();

        if (quantity == '' || quantity <= 0) {
            quantity = 1;
            $(this).val(quantity);
            parent.find('.minus').attr('disabled', true);
        } else if (quantity == 1) {
            parent.find('.minus').attr('disabled', true);
        } else {
            parent.find('.minus').removeAttr('disabled');
        }

        if ($(this).parent().data('update') != 'no') {
            updateCart(quantity, $(this).parents('.cartItem'));
        }
    }

    // add to cart handler
    function addToCartClickHandler() {
        let productType = $(this).data('product_type');
        let productId = $(this).data('id');
        let attributeValues = $(this).parents('.product-details').find('.attributeBtn.active').map((i, element) => $(element).data('attribute').id).toArray();


        if (productType == 2 && attributeValues.length < 1) {
            notify('error', 'Please select variants');
            return false;
        }

        let quantity = $(this).parent().find('[name=quantity]').val();
        if (quantity == null) {
            notify('error', 'The quantity field is required');
            return;
        }

        var data = {
            _token: "{{ csrf_token() }}",
            attribute_values: attributeValues,
            quantity: quantity,
        }

        let addToCartUrl = "{{ route('cart.add', ':id') }}".replace(':id', productId);

        $.post(addToCartUrl, data).done((response) => {
            if (response.status == 'success') {
                setPartialCart(response.partialCartData);
                setCartCount(response.cartItemCount);
                setCartSubtotal(response.cartSubtotal);
            }
            notify(response.status, response.message);
        });
    }

    const setCompareWishlistAndCartData = () => {
        $.ajax({
            type: "GET",
            url: "{{ route('product.compare.wishlist.cart.data') }}",
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                    let data = response.data;

                    if (data.compare_products > 0) {
                        $('.compare-count').text(data.compare_products);
                        $('.compare-count').removeClass('d-none');
                    } else {
                        $('.compare-count').addClass('d-none');
                    }

                    if (data.wishlist_products > 0) {
                        $('.wishlist-count').text(data.wishlist_products);
                        $('.wishlist-count').removeClass('d-none');
                    } else {
                        $('.wishlist-count').addClass('d-none');
                    }

                    $('.cartSubtotal').text(data.cart_subtotal);

                    if(data.cart_products > 0) {
                        $('.header-middle .cartItemCount').text(data.cart_products).removeClass('d-none');
                    }else{
                        $('.header-middle .cartItemCount').text(0).addClass('d-none');
                    }

                    $('.cartItemCount').text(data.cart_products);

                } else {
                    console.warn(response.message);
                }
            }
        });
    }

    let isLoadedWishlistData = false;
    const wishBtnClickHandler = () => {
        if (isLoadedWishlistData) return false;
        $.ajax({
            type: "GET",
            url: "{{ route('wishlist.shortlist') }}",
            success: function(response) {
                $('.wish--products').html(response);
                lazyload();
                isLoadedWishlistData = true;
            }
        });
    }

    let isLoadedCartData = false;
    const cartBtnClickHandler = () => {
        if (isLoadedCartData) return false;
        $.ajax({
            type: "GET",
            url: "{{ route('cart.shortlist') }}",
            success: function(response) {
                $('.cart--products').html(response);
                lazyload();
                isLoadedCartData = true;
            }
        });
    }

    $(document).on('click', '#loadMoreBtn', loadMoreClickHandler);
    $(document).on('click', '.quickViewBtn', quickViewClickHandler);
    $(document).on("click", ".qtyButton", quantityButtonClickHandler);
    $(document).on('click', ' #applyCoupon', applyCouponClickHandler);
    $(document).on('click', '.removeCart', removeFromCartHandler);
    $(document).on('click', '.removeCoupon', removeCouponClickHandler);
    $(document).on('click', '.addToWishlist', addToWishlistClickHandler);
    $(document).on('click', '.removeWishlist', removeFromWishlistClickHandler);
    $(document).on('click', '.addToCompare', addToCompareClickHandler);
    $(document).on('change, input', '[name=quantity]', quantityChangeHandler);
    $(document).on('keyup', '[name=coupon_code]', couponCodeKeyHandler);
    $(document).on('click', '.addToCart', addToCartClickHandler);
    $(document).on('click', '.wish-button', wishBtnClickHandler);
    $(document).on('click', '.cart-button', cartBtnClickHandler);

    lazyload();
    setCompareWishlistAndCartData();
</script>
