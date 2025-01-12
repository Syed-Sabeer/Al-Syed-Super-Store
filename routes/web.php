<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});


// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('CartController')->name('cart.')->group(function () {
    Route::get('cart', 'cart')->name('page');
    Route::post('add-to-cart/{productId}', 'addToCart')->name('add');
    Route::post('cart-update/{id}', 'updateCartItem')->name('update');
    Route::get('cart-shortlist', 'partialCart')->name('shortlist');
    Route::get('cart-items-count', 'cartItemsCount')->name('items.count');
    Route::get('cart-subtotal', 'cartSubtotal')->name('items.subtotal');
    Route::post('remove-from-cart/{id}', 'removeCartItem')->name('remove');

    Route::post('apply-coupon', 'applyCoupon')->name('coupon.apply');
    Route::post('remove-coupon', 'removeCoupon')->name('coupon.remove');
});

Route::controller('WishlistController')->name('wishlist.')->group(function () {
    Route::get('wishlist', 'wishList')->name('page');
    Route::post('add-to-wishlist/{productId}', 'addToWishList')->name('add');
    Route::get('wishlist-short', 'partialWishlist')->name('shortlist');
    Route::get('wishlist-count', 'wishlistItemsCount')->name('items.count');
    Route::post('remove-from-wishlist/{id}', 'remove')->name('remove');
});

Route::controller('ProductController')->name('product.')->group(function () {
    Route::get('products', 'products')->name('all');
    // category products
    Route::get('products/{category}', 'productByCategory')->name('by.category');
    // brand products
    Route::get('{slug}/products', 'productsByBrand')->name('by.brand');
    // product details
    Route::get('product/{slug}', 'productDetails')->name('detail');

    Route::get('products/{id}/reviews', 'reviews')->name('reviews');
    Route::get('product/{slug}/stock-by-variant', 'geStockByVariant')->name('variant.stock');
    Route::get('images-by-variant/{productId}', 'getImagesByVariant')->name('variant.image');
    Route::get('compare-wishlist-cart-date', 'compareWishlistAndCartData')->name('compare.wishlist.cart.data');
});

Route::controller('CompareController')->name('compare.')->group(function () {
    Route::get('compare-products', 'compare')->name('all');
    Route::post('add-to-compare', 'addToCompare')->name('add');
    Route::get('compare-products-count', 'compareProductsCount')->name('count');
    Route::post('remove-from-compare/{id?}', 'removeFromCompare')->name('remove');
});

Route::controller('SiteController')->group(function () {
    Route::get('categories', 'categories')->name('categories');
    Route::get('brands', 'brands')->name('brands');
    Route::get('track-order', 'trackOrder')->name('order.track');
    Route::post('track-order', 'getOrderTrackData');
    Route::post('subscribe', 'addSubscriber')->name('subscribe');
    Route::get('faq', 'faq')->name('faq');
    Route::get('about-us', 'about')->name('about');

    // offer
    Route::get('offers', 'offers')->name('offers');
    Route::get('offer-products/{id}', 'offerProducts')->name('offer.products');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('contact-submit', 'contactSubmit')->name('contact.submit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/', 'index')->name('home');
});
