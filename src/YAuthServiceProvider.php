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
            __DIR__ . '/../config' => base_path('config'),
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
}
