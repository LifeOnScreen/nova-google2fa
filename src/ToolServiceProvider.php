<?php

namespace Lifeonscreen\Google2fa;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Lifeonscreen\Google2fa\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'google2fa');
        $this->loadViewsFrom(__DIR__ . '/../../../laravel/nova/resources/views', 'nova');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->publishes([
                __DIR__ . '/../config/lifeonscreen2fa.php' => config_path('lifeonscreen2fa.php'),
            ], 'lifeonscreen2fa.config');

            // Publishing the migrations.
            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/google2fa'),
            ], 'views');
        }

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('los/2fa')
            ->group(__DIR__ . '/../routes/api.php');

        Route::middleware('web')
            ->prefix('los/2fa')
            ->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/lifeonscreen2fa.php', 'lifeonscreen2fa');
    }
}
