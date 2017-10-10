<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
            require app_path('Http/dev_routes.php');
            require app_path('Http/live_custom_routes.php');
            require app_path('Http/new_routes.php');
            require app_path('Http/shop_routes.php');
	        require app_path('Http/sitemap_routes.php');
			require app_path('Http/account_routes.php');
            require app_path('Http/api_routes.php');
            require app_path('Http/dynamic_routes.php');
        });
    }
}
