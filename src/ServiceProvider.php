<?php

namespace Cruddy;

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
            __DIR__ . '/../config/cruddy.php' => config_path('cruddy.php'),
        ]);

        // Publish stub files
        $this->publishes([
            __DIR__ . '/Commands/stubs/' => base_path('/stubs/cruddy/'),
        ]);

        // Add new Cruddy database configuration to the config.
        $ignoredAppEnvs = [
            'testing',
            'local',
        ];

        if (!in_array(env('APP_ENV'), $ignoredAppEnvs)) {
            $this->setDatabaseConnection();
        }

    }
}
