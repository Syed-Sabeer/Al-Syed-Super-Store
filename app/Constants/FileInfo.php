<?php

namespace App\Constants;

class FileInfo {

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo() {
        $data['depositVerify'] = [
            'path'      => 'assets/images/verify/deposit'
        ];
        $data['verify'] = [
            'path'      => 'assets/verify'
        ];
        $data['default'] = [
            'path'      => 'assets/images/default.png',
        ];
        $data['ticket'] = [
            'path'      => 'assets/support',
        ];
        $data['logoIcon'] = [
            'path'      => 'assets/images/logo_icon',
        ];

        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['extensions'] = [
            'path'      => 'assets/images/extensions',
            'size'      => '36x36',
        ];
        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];
        $data['userProfile'] = [
            'path'      => 'assets/images/user/profile',
            'size'      => '350x300',
        ];
        $data['adminProfile'] = [
            'path'      => 'assets/admin/images/profile',
            'size'      => '400x400',
        ];
        $data['avatar'] = [
            'path' => 'assets/images/avatar.png',
        ];

        $data['digitalProductFile'] = [
            'path' => 'assets/digital_product',
        ];

        $data['attribute'] = [
            'path' => 'assets/images/attribute_values',
            'size' => '64x64',
        ];
        $data['offerBanner'] = [
            'path'  => 'assets/images/offer_banner',
            'size'  => '450x616',
        ];

        $data['product'] = [
            'path'  => 'assets/images/product',
            'size'  => '800x800',
            'thumb' => '300x300',
        ];

        $data['categoryIcon'] = [
            'path' => 'assets/images/category/icons',
            'size' => '40x40',
        ];

        $data['category'] = [
            'path' => 'assets/images/category',
            'size' => '200x200',
        ];

        $data['brand'] = [
            'path' => 'assets/images/brand',
            'size' => '300x200',
        ];
        $data['push'] = [
            'path'      => 'assets/images/push_notification',
        ];
        $data['appPurchase'] = [
            'path'      => 'assets/in_app_purchase_config',
        ];
        $data['maintenance'] = [
            'path'      => 'assets/images/maintenance',
            'size'      => '660x325',
        ];
        $data['language'] = [
            'path' => 'assets/images/language',
            'size' => '50x50'
        ];
        $data['gateway'] = [
            'path' => 'assets/images/gateway',
            'size' => ''
        ];
        $data['pushConfig'] = [
            'path'      => 'assets/admin',
        ];
        $data['svg'] = [
            'path'      => 'assets/images/svg',
        ];

        $data['collection'] = [
            'path'  => 'assets/images/collection',
            'size'  => '450x616',
        ];

        $data['singlePromoBanner'] = [
            'path'      => 'assets/images/promo_banner',
            'size'      => '1400x270',
        ];

        $data['doublePromoBanner'] = [
            'path'      => 'assets/images/promo_banner',
            'size'      => '690x233',
        ];

        $data['triplePromoBanner'] = [
            'path'      => 'assets/images/promo_banner',
            'size'      => '460x230',
        ];

        return $data;
    }
}
