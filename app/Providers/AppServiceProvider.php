<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        view()->composer('*', function ($view) {
            $excludedViews = ['admin.pos.partials.product', 'admin.fos.partials.product'];

            if (!in_array($view->getName(), $excludedViews)) {
                $view->with('settings', Setting::find(1));
            }
        });

        Paginator::useBootstrap();
    }
}
