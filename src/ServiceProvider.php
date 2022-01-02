<?php

namespace Cruddy;

use Cruddy\Commands\ControllerMakeCommand;
use Cruddy\Commands\ModelMakeCommand;
use Cruddy\Commands\RequestMakeCommand;
use Cruddy\Commands\RouteAddCommand;
use Cruddy\Commands\ViewMakeCommand;
use Cruddy\Commands\VueImportAddCommand;
use Cruddy\StubEditors\Inputs\Input\StubInputEditorFactory;
use Cruddy\StubEditors\StubEditor;
use Cruddy\StubEditors\StubEditorFactory;
use Cruddy\Traits\ConfigTrait;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\ForeignKeyValidation\ForeignKeyValidationFactory;
use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\StubEditors\Inputs\Input\StubInputEditor;
use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    use ConfigTrait;

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

        // Bind the ForeignKeyValidation
        $this->app->bind(ForeignKeyValidation::class, function ($app, $params) {
            $foreignKey = $params[0];

            return ForeignKeyValidationFactory::get($foreignKey);
        });

        // Bind the ModelRelationship
        $this->app->bind(ModelRelationship::class, function ($app, $params) {
            $foreignKey = $params[0];

            return new ModelRelationship($foreignKey);
        });

        // Bind the StubEditor
        $this->app->bind(StubEditor::class, function ($app, $params) {
            $stubEditorType = $params[0];
            $stub = $params[1] ?? '';

            return StubEditorFactory::get($stubEditorType, $stub);
        });

        // Bind the StubInputsEditor
        $this->app->bind(StubInputsEditor::class, function ($app, $params) {
            $inputs = $params[0];
            $type = $params[1];
            $stub = $params[2] ?? '';

            return new StubInputsEditor($inputs, $type, $stub);
        });

        // Bind the StubInputEditor
        $this->app->bind(StubInputEditor::class, function ($app, $params) {
            $column = $params[0];
            $type = $params[1];
            $stub = count($params) > 2 ? $params[2] : '';

            return StubInputEditorFactory::get($column, $type, $stub);
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
