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
        $this->registerMigration();
        $this->registerView();
        $this->registerResource();
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
            __DIR__ . '/../config' => config_path(),
        ], 'yauth-config');

    }

    /**
     * 注册迁移
     */
    private function registerMigration(){
        if(YAuth::$runsMigrations){
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'yauth-migrations');
    }

    /**
     * 注册视图
     */
    private function registerView(){
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'yauth');
//        $this->publishes([
//            __DIR__.'/../resources/views' => base_path('resources/views/vendor/yauth'),
//        ], 'yauth-views');
    }

    /**
     * 注册资源
     */
    private function registerResource(){
        $this->publishes([
            __DIR__.'/../resources/yauth-assets' => public_path('vendor/yauth-assets'),
        ], 'yauth-public');
    }

    public function provides()
    {

    }
}
