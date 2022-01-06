<?php

namespace Cruddy;

use Cruddy\Commands\ControllerMakeCommand;
use Cruddy\Commands\ModelMakeCommand;
use Cruddy\Commands\RequestMakeCommand;
use Cruddy\Commands\RouteAddCommand;
use Cruddy\Commands\ViewMakeCommand;
use Cruddy\Commands\VueImportAddCommand;
use Cruddy\ForeignKeyInputs\ForeignKeyInput;
use Cruddy\ForeignKeyInputs\ForeignKeyInputFactory;
use Cruddy\StubEditors\StubEditor;
use Cruddy\StubEditors\StubEditorFactory;
use Cruddy\Traits\ConfigTrait;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\StubEditors\ControllerStubEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\HasOneInput;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\InputFactory;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Cruddy\StubEditors\ModelStubEditor;
use Cruddy\StubEditors\RequestStubEditor;
use Cruddy\StubEditors\ViewStubEditor;
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

            return new ForeignKeyValidation($foreignKey);
        });

        // Bind the ForeignKeyInput
        $this->app->bind(ForeignKeyInput::class, function ($app, $params) {
            $foreignKey = $params[0];

            return (new InputFactory($foreignKey))
                ->get();
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

            return (new StubEditorFactory($stubEditorType, $stub))
                ->get();
        });

        // Bind the StubInputsEditor
        $this->app->bind(StubInputsEditor::class, function ($app, $params) {
            $inputs = $params[0];
            $type = $params[1];
            $stub = $params[2] ?? '';

            return new StubInputsEditor($inputs, $type, $stub);
        });

        // Bind the InputStubEditor
        $this->app->bind(InputStubEditor::class, function ($app, $params) {
            $column = $params[0];
            $type = $params[1];
            $stub = count($params) > 2 ? $params[2] : '';

            return (new InputStubEditorFactory())
                ->get($column, $type, $stub);
        });

        // Bind the HasOneInput
        $this->app->bind(HasOneInput::class, function ($app, $params) {
            $foreignKey = $params[0];

            return new HasOneInput($foreignKey);
        });

        // Bind the ControllerStubEditor
        $this->app->bind(ControllerStubEditor::class, function ($app, $params) {
            $stub = $params[0];

            return new ControllerStubEditor($stub);
        });

        // Bind the ModelStubEditor
        $this->app->bind(ModelStubEditor::class, function ($app, $params) {
            $stub = $params[0];

            return new ModelStubEditor($stub);
        });

        // Bind the RequestStubEditor
        $this->app->bind(RequestStubEditor::class, function ($app, $params) {
            $stub = $params[0];

            return new RequestStubEditor($stub);
        });

        // Bind the ViewStubEditor
        $this->app->bind(ViewStubEditor::class, function ($app, $params) {
            $stub = $params[0];

            return new ViewStubEditor($stub);
        });





        // Bind the ColumnInputStubEditorFactory
        $this->app->bind(ColumnInputStubEditorFactory::class, function ($app, $params) {
            $column = $params[0];
            $inputStubEditor = $params[1];
            $stub = $params[2];

            return new ColumnInputStubEditorFactory($column, $inputStubEditor, $stub);
        });

        // Bind the ForeignKeyInputStubEditorFactory
        $this->app->bind(ForeignKeyInputStubEditorFactory::class, function ($app, $params) {
            $column = $params[0];

            return new ForeignKeyInputStubEditorFactory($column);
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
