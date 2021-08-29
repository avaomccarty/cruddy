<?php

namespace Cruddy;

use Cruddy\Commands\ControllerMakeCommand;
use Cruddy\Commands\ModelMakeCommand;
use Cruddy\Commands\RequestMakeCommand;
use Cruddy\Commands\RouteAddCommand;
use Cruddy\Commands\ViewMakeCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Extend the DB Facade to use the Connection class.
        DB::extend('cruddy', function ($app) {
            return new Connection(DB::connection()->getPdo());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__.'/../config/cruddy.php' => config_path('cruddy.php'),
        ]);

        // Add new Cruddy database configuration to the config.
        // Note: This seems off. Won't this just cache this confguration? But then the driver would be removed if the cache is busted.
        Config::set('database.connections.cruddy', Config::get('cruddy.database.connections.cruddy'));

        // Include commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerMakeCommand::class,
                RequestMakeCommand::class,
                RouteAddCommand::class,
                ViewMakeCommand::class,
                ModelMakeCommand::class,
            ]);
        }
    }
}
