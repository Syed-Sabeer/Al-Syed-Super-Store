<?php

namespace App\Providers;

use App\Constants\Status;
use App\Lib\Searchable;
use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Skip the SystemInstalled check during CLI operations
        if (app()->runningInConsole()) {
            return;
        }

        if (!cache()->get('SystemInstalled')) {
            $envFilePath = base_path('.env');
            if (!file_exists($envFilePath)) {
                header('Location: install');
                exit;
            }
            $envContents = file_get_contents($envFilePath);
            if (empty($envContents)) {
                header('Location: install');
                exit;
            } else {
                cache()->put('SystemInstalled', true);
            }
        }

        $activeTemplate = activeTemplate();
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['emptyMessage'] = 'Data not found';

        $viewShare['parentCategories'] = Category::isParent()
            ->with([
                'specialProducts.brand',
                'allSubcategories' => function ($q) {
                    $q->orderBy('position');
                },
                'products' => function ($product) {
                    return $product->published();
                },
                'products.reviews',
                'products'
            ])
            ->orderBy('position')->get();

        view()->share($viewShare);

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount' => User::banned()->count(),
                // 'emailUnverifiedUsersCount' => User::emailUnverified()->count(),
                // 'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'pendingTicketCount' => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount' => Deposit::pending()->count(),

                'pendingOrdersCount' => Order::isValidOrder()->pending()->count(),
                'processingOrdersCount' => Order::isValidOrder()->processing()->count(),
                'dispatchedOrdersCount' => Order::isValidOrder()->dispatched()->count(),

                'lowStockProductsCount' => Product::lowStock()->count(),
                'outOfStockProductsCount' => Product::outOfStock()->count(),

                'updateAvailable' => version_compare(gs('available_version'), systemDetails()['version'], '>') ? 'v' . gs('available_version') : false,
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications' => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        if (gs('force_ssl')) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
    }
}
