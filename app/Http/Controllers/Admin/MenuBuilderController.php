<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MenuBuilderController extends Controller
{
    public function all()
    {
        $pageTitle = 'All Menu';

        return view('admin.menu_builder.index', compact('pageTitle'));
    }

    public function updateHeadersOrder(Request $request){
        $setting = Frontend::where('data_keys', 'headers.order.content')->first();
        $setting->data_values = $request->input('headers');
        $setting->save();
        $notify[] = ['success', 'Headers order updated successfully'];
        return back()->withNotify($notify);
    }

    public function headerOne()
    {
        $pageTitle = 'Header One';
        $links = $this->getPagesData();
        $setting = Frontend::where('data_keys', 'header_one.content')->first()?->data_values;
        $menus = $setting->links??[];
        return view('admin.menu_builder.header_one', compact('pageTitle', 'links', 'menus', 'setting'));
    }

    public function updateHeaderOne(Request $request) {

        $request->validate([
            'links' => 'nullable|array|min:1',
            'links.*' => 'required|array|size:2',
            'links.*.name' => 'required|string',
            'links.*.url' => 'required|string',
        ]);

        $row = Frontend::where('data_keys', 'header_one.content')->firstOrNew();

        $values = [
            'status' => $request->status ?? 'off',
            'language_option' => $request->language_option ?? 'off',
            'user_option' => $request->user_option ?? 'off',
            'links_position' => $request->links_position ?? 'left',
            'links' => $request->links,
        ];

        $row->data_keys = 'header_one.content';
        $row->data_values = $values;
        $row->save();

        $notify[] = ['success', 'Menu updated successfully'];
        return back()->withNotify($notify);
    }

    public function headerTwo() {
        $pageTitle = 'Header Two';
        $links = $this->getPagesData();
        $settings = Frontend::where('data_keys', 'header_two.content')->first()?->data_values;
        return view('admin.menu_builder.header_two', compact('pageTitle', 'links', 'settings'));
    }

    public function updateHeaderTwo(Request $request) {
        $request->validate([
            'status' => 'nullable|in:on',
            'group' => 'required|array|size:3',
            'group.*logo_widget' => 'required|array',
            'group.*search_widget' => 'required|array',
            'group.*widgets' => 'required|array',
        ]);

        $row = Frontend::where('data_keys', 'header_two.content')->first();

        $requestData = $request->except('_token');

        $row->data_values = $requestData;
        $row->save();

        $notify[] = ['success', 'Menu updated successfully'];
        return back()->withNotify($notify);
    }

    public function headerThree() {
        $pageTitle = 'Header Three';
        $links = $this->getPagesData();
        $settings = Frontend::where('data_keys', 'header_three.content')->first()?->data_values;
        return view('admin.menu_builder.header_three', compact('pageTitle', 'links', 'settings'));
    }

    public function updateHeaderThree(Request $request) {

        $request->validate([
            'status' => 'nullable|in:on',
            'group' => 'required|array|size:3',
            'group.*links' => 'required|array',
            'group.*category_widget' => 'required|array|size:1',
            'group.*widgets' => 'required|array',
            'group.links.*.name' => 'required|string',
            'group.links.*.url' => 'required|string',
            'background_color' => 'nullable|regex:/^[a-f0-9]{6}$/i'
        ]);

        $row = Frontend::where('data_keys', 'header_three.content')->first();

        $requestData = $request->except('_token');

        $row->data_values = $requestData;
        $row->save();

        $notify[] = ['success', 'Menu updated successfully'];
        return back()->withNotify($notify);
    }

    public function footerMenu()
    {
        $pageTitle = 'Update Footer Menu';
        $links = $this->getPagesData();
        $menus = Frontend::where('data_keys', 'footer_menu.content')->first()?->data_values;
        return view('admin.menu_builder.footer_menu', compact('pageTitle', 'links', 'menus'));
    }

    public function updateFooterMenu(Request $request)
    {
        $request->validate([
            'groups' => 'nullable|array|min:1',
            'groups.*.title' => 'required|string',
            'groups.*.links' => 'required|array|min:1',
            'groups.*.links.*.name' => 'required|string',
            'groups.*.links.*.url' => 'required|string'
        ]);

        $row = Frontend::where('data_keys', 'footer_menu.content')->first();
        $row->data_values = $request->groups;
        $row->save();

        $notify[] = ['success', 'Footer menu updated successfully'];
        return back()->withNotify($notify);
    }


    private function getPagesData()
    {
        $routes = Route::getRoutes();

        $policyPages = Frontend::where('data_keys', 'policy_pages.element')
        ->where('tempname', activeTemplateName())
        ->orderBy('id', 'desc')
        ->get();

        $policyPagesUrls = [];

        if(!blank($policyPages)){
            foreach ($policyPages as $policyPage) {
                $policyPagesUrls[] = [
                    'name' => $policyPage->data_values->title,
                    'uri' => str_replace(route('home').'/', '' ,route('policy.pages',$policyPage->slug)),
                ];
            }
        }


        return json_decode(
            json_encode([
                [
                    'name' => 'Home',
                    'uri' => $routes->getByName('home')->uri()
                ],
                [
                    'name' => 'Contact',
                    'uri' => $routes->getByName('contact')->uri()
                ],
                [
                    'name' => 'Offers',
                    'uri' => $routes->getByName('offers')->uri()
                ],
                [
                    'name' => 'About Us',
                    'uri' => $routes->getByName('about')->uri()
                ],
                [
                    'name' => 'FAQ',
                    'uri' => $routes->getByName('faq')->uri()
                ],
                [
                    'name' => 'Track Order',
                    'uri' => $routes->getByName('order.track')->uri()
                ],
                [
                    'name' => 'Brands',
                    'uri' => $routes->getByName('brands')->uri()
                ],
                [
                    'name' => 'Categories',
                    'uri' => $routes->getByName('categories')->uri()
                ],
                [
                    'name' => 'Compare',
                    'uri' => $routes->getByName('compare.all')->uri()
                ],
                [
                    'name' => 'Products',
                    'uri' => $routes->getByName('product.all')->uri()
                ],
                [
                    'name' => 'Wishlist',
                    'uri' => $routes->getByName('wishlist.page')->uri()
                ],
                [
                    'name' => 'Cart',
                    'uri' => $routes->getByName('cart.page')->uri()
                ],
                [
                    'name' => 'Login',
                    'uri' => $routes->getByName('user.login')->uri()
                ],
                [
                    'name' => 'Registration',
                    'uri' => $routes->getByName('user.register')->uri()
                ],
                [
                    'name' => 'Cookie Policy',
                    'uri' => $routes->getByName('cookie.policy')->uri()
                ],

                ...$policyPagesUrls
            ])
        );
    }
}
