<?php

use App\Http\Middleware\CheckModuleIsEnabled;
use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->middleware('guest')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
    });

    Route::controller('RegisterController')->middleware(['guest'])->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register');
        Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function () {
        // Route::get('authorization', 'authorizeForm')->name('authorization');
        // Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        // Route::post('verify-email', 'emailVerification')->name('verify.email');
        // Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
    });

    Route::middleware(['check.status', 'registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::any('payment/history', 'depositHistory')->name('deposit.history');
                Route::post('add-device-token', 'addDeviceToken')->name('add.device.token');
                Route::get('notifications', 'notifications')->name('notifications');
                Route::get('read-notification/{id}', 'readNotification')->name('notification.read');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');

                Route::get('shipping-address', 'shippingAddress')->name('shipping.address');
                Route::post('shipping-address/store/{id?}', 'saveShippingAddress')->name('shipping.address.store');
                Route::post('shipping-address/delete/{id}', 'deleteShippingAddress')->name('shipping.address.delete');
            });

            Route::name('checkout.')->group(function () {
                Route::controller('CheckoutController')->group(function () {
                    Route::get('checkout/shipping-info', 'shippingInfo')->name('shipping.info')->middleware('checkout.step:shipping_info');
                    Route::post('add-shipping-address', 'addShippingInfo')->name('shipping.info.add')->middleware('checkout.step:shipping_info');

                    Route::get('checkout/delivery-methods', 'deliveryMethods')->name('delivery.methods')->middleware('checkout.step:delivery_method');
                    Route::post('add-delivery-method', 'addDeliveryMethod')->name('delivery.method.add')->middleware('checkout.step:delivery_method');
                    Route::get('order-confirmation/{order}', 'confirmation')->name('confirmation');
                });

                Route::controller('PaymentController')->group(function () {
                    Route::get('checkout/payment-methods', 'paymentMethods')->name('payment.methods')->middleware('checkout.step:payment');
                    Route::post('complete-checkout', 'completeCheckout')->name('complete')->middleware('checkout.step:payment');
                });
            });

            Route::controller('OrderController')->group(function () {
                Route::get('orders/{type}', 'orders')->name('orders');
                Route::get('order/{order_number}', 'orderDetails')->name('order');
                Route::get('print/{order}', 'printInvoice')->name('print.invoice');
                Route::get('digital-item/download/{id}', 'download')->name('order.item.download');
            });

            Route::controller('ReviewController')->middleware('checkModule:product_review')->name('review.')->group(function () {
                Route::get('product-reviews', 'index')->name('index');
                Route::post('review/add', 'add')->name('add');
            });
        });

        // Payment
        Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
