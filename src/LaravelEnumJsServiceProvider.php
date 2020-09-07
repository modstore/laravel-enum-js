<?php

namespace Modstore\LaravelEnumJs;

use Illuminate\Support\ServiceProvider;
use Modstore\LaravelEnumJs\Console\Commands\GenerateCommand;

class LaravelEnumJsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-enum-js.php'),
            ], 'config');

            $this->commands([
                GenerateCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-enum-js');
    }
}
