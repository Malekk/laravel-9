<?php

namespace App\Providers;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\ElasticsearchHelper;
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
        $this->app->bind(ElasticsearchHelperInterface::class, ElasticsearchHelper::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
