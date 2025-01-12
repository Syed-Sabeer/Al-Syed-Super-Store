<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\View;


class ActiveTemplateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $viewShare['activeTemplate']     = activeTemplate();
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        view()->share($viewShare);

        view()->composer(['Template::partials.menu.site_menu', 'Template::partials.footer'], function ($view) {
            $view->with([
                'pages' => Page::where('is_default', Status::NO)->where('tempname', activeTemplate())->orderBy('id', 'DESC')->get()
            ]);
        });

        View::addNamespace('Template', resource_path('views/templates/' . activeTemplateName()));
        return $next($request);
    }
}
