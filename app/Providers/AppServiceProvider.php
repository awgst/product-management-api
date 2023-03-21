<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            'App\Repository\Category\CategoryRepositoryInterface',
            'App\Repository\Category\EloquentCategoryRepository'
        );
        $this->app->bind(
            'App\Repository\Product\ProductRepositoryInterface',
            'App\Repository\Product\EloquentProductRepository'
        );
    }
}
