<?php

namespace Sowork\YAuth;

use Illuminate\Support\ServiceProvider;

class YAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('yauth', function ($app) {
            return new YAuth();
        });
        // 发布配置
        $this->publishes([
            __DIR__.'/config/yauth.php' => config_path('yauth.php'),
        ], 'yauth');
    }

    public function provides()
    {

    }
}
