<?php

namespace Cruddy;

// use Cruddy\Commands\ControllerMakeCommand;
// use Cruddy\Commands\ModelMakeCommand;
// use Cruddy\Commands\RequestMakeCommand;
// use Cruddy\Commands\RouteAddCommand;
// use Cruddy\Commands\ViewMakeCommand;
// use Cruddy\Commands\VueImportAddCommand;
// use Cruddy\StubEditors\StubEditor;
// use Cruddy\StubEditors\StubEditorFactory;
// use Cruddy\Traits\ConfigTrait;
// use Cruddy\StubEditors\Validation\ForeignKeyValidationStub;
// use Cruddy\ModelRelationships\ModelRelationship;
// use Cruddy\StubEditors\ControllerStub;
// use Cruddy\StubEditors\Inputs\ColumnInputsStub;
// use Cruddy\StubEditors\Inputs\ForeignKeyInputsStub;
// use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubFactory;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\AllAttributes;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\AttributeInteractor;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Checked;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Disabled;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Attribute;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Min;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Value;
// use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStub;
// use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubFactory;
// use Cruddy\StubEditors\Inputs\Input\ForeignKeys\HasOneInput;
// use Cruddy\StubEditors\Inputs\Input\InputStub;
// use Cruddy\StubEditors\Inputs\Input\InputStubFactory;
// use Cruddy\StubEditors\Inputs\InputsStubInteractor;
// use Cruddy\StubEditors\ModelStub;
// use Cruddy\StubEditors\RequestStub;
// use Cruddy\StubEditors\StubPlaceholderInteractor;
// use Cruddy\StubEditors\ViewStub;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    // use ConfigTrait;

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

        // Include commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerMakeCommand::class,
                RequestMakeCommand::class,
                RouteAddCommand::class,
                ViewMakeCommand::class,
                VueImportAddCommand::class,
                ModelMakeCommand::class,
            ]);
        }
    }
}
