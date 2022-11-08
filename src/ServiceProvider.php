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
        $this->publishConfigFile();
        $this->publishStubFolder();
        $this->addDatabaseConnection();
    }

    /**
     * Publish the config file within the Laravel application.
     *
     * @return void
     */
    protected function publishConfigFile() : void
    {
        $this->publishes([
            __DIR__ . '/config/cruddy.php' => config_path('cruddy.php'),
        ]);
    }

    /**
     * Publish the stubs folder within the Laravel application.
     *
     * @return void
     */
    protected function publishStubFolder() : void
    {
        $this->publishes([
            __DIR__ . '/stubs/' => base_path('/stubs/cruddy/'),
        ]);
    }

    /**
     * Add new Cruddy database configuration to the config.
     *
     * @return void
     */
    protected function addDatabaseConnection() : void
    {
        $ignoredAppEnvs = [
            'testing',
            'local',
        ];

        if (!in_array(env('APP_ENV'), $ignoredAppEnvs)) {
            $this->setDatabaseConnection();
        }
    }
}
