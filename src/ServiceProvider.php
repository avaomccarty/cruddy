<?php

namespace Cruddy;

use Cruddy\Commands\ControllerMakeCommand;
use Cruddy\Commands\ModelMakeCommand;
use Cruddy\Commands\RequestMakeCommand;
use Cruddy\Commands\RouteAddCommand;
use Cruddy\Commands\ViewMakeCommand;
use Cruddy\Commands\VueImportAddCommand;
use Cruddy\StubEditors\StubEditor;
use Cruddy\StubEditors\StubEditorFactory;
use Cruddy\Traits\ConfigTrait;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\StubEditors\ControllerStubEditor;
use Cruddy\StubEditors\Inputs\ColumnInputsStubEditor;
use Cruddy\StubEditors\Inputs\ForeignKeyInputsStubEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubEditor;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\HasOneInput;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Cruddy\StubEditors\Inputs\Input\InputStubEditorFactory;
use Cruddy\StubEditors\Inputs\InputsStubEditorInteractor;
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
            return new ForeignKeyValidation(...$params);
        });

        // Bind the ForeignKeyInputStubEditor
        $this->app->bind(ForeignKeyInputStubEditor::class, function ($app, $params) {
            return (new ForeignKeyInputStubEditorFactory(...$params))
                ->get();
        });

        // Bind the ModelRelationship
        $this->app->bind(ModelRelationship::class, function ($app, $params) {
            return new ModelRelationship(...$params);
        });

        // Bind the StubEditor
        $this->app->bind(StubEditor::class, function ($app, $params) {
            return (new StubEditorFactory(...$params))
                ->get();
        });

        // Bind the InputsStubEditorInteractor
        $this->app->bind(InputsStubEditorInteractor::class, function ($app, $params) {
            return new InputsStubEditorInteractor(...$params);
        });

        // Bind the InputStubEditor
        $this->app->bind(InputStubEditor::class, function ($app, $params) {
            return (new InputStubEditorFactory(...$params))
                ->get();
        });

        // Bind the HasOneInput
        $this->app->bind(HasOneInput::class, function ($app, $params) {
            return new HasOneInput(...$params);
        });

        // Bind the ControllerStubEditor
        $this->app->bind(ControllerStubEditor::class, function ($app, $params) {
            return new ControllerStubEditor(...$params);
        });

        // Bind the ModelStubEditor
        $this->app->bind(ModelStubEditor::class, function ($app, $params) {
            return new ModelStubEditor(...$params);
        });

        // Bind the RequestStubEditor
        $this->app->bind(RequestStubEditor::class, function ($app, $params) {
            return new RequestStubEditor(...$params);
        });

        // Bind the ViewStubEditor
        $this->app->bind(ViewStubEditor::class, function ($app, $params) {
            return new ViewStubEditor(...$params);
        });

        // Bind the ColumnInputStubEditorFactory
        $this->app->bind(ColumnInputStubEditorFactory::class, function ($app, $params) {
            return new ColumnInputStubEditorFactory(...$params);
        });

        // Bind the ForeignKeyInputStubEditorFactory
        $this->app->bind(ForeignKeyInputStubEditorFactory::class, function ($app, $params) {
            return new ForeignKeyInputStubEditorFactory(...$params);
        });

        // Bind the ForeignKeyInputsStubEditor
        $this->app->bind(ForeignKeyInputsStubEditor::class, function ($app, $params) {
            return new ForeignKeyInputsStubEditor(...$params);
        });

        // Bind the ColumnInputsStubEditor
        $this->app->bind(ColumnInputsStubEditor::class, function ($app, $params) {
            return new ColumnInputsStubEditor(...$params);
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
