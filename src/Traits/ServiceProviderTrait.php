<?php

namespace Cruddy\Traits;

use Attribute;
use Cruddy\Connection;
use Cruddy\ModelRelationships\ModelRelationship;
// use Cruddy\StubEditors\ControllerStub;
use Cruddy\StubEditors\StubEditor;
use Cruddy\StubEditors\StubEditorFactory;
// use Cruddy\StubEditors\ModelStub;
// use Cruddy\StubEditors\ViewStub;
// use Cruddy\StubEditors\RequestStub;
use Cruddy\StubEditors\Inputs\ColumnInputsStub;
use Cruddy\StubEditors\Inputs\ForeignKeyInputsStub;
use Cruddy\StubEditors\Inputs\Input\Columns\Factory as ColumnFactory;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\AllAttributes;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\AttributeInteractor;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes\Checked;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes\Disabled;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes\Min;
use Cruddy\StubEditors\Inputs\Input\Columns\Input\Attributes\Value;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Checked;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Disabled;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Attribute;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Min;
// use Cruddy\StubEditors\Inputs\Input\Columns\Input\Value;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStub;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\HasOneInput;
use Cruddy\StubEditors\Inputs\Input\InputStub;
use Cruddy\StubEditors\Inputs\Input\InputStubFactory;
use Cruddy\StubEditors\Inputs\InputsStubInteractor;
use Cruddy\StubEditors\Validation\ForeignKeyValidationStub;
use Cruddy\Stubs\Interactors\StubPlaceholderInteractor;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\ControllerStub;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\ModelStub;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\RequestStub;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\ViewStub;
use Illuminate\Support\Facades\DB;

trait ServiceProviderTrait
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

        // Bind the ForeignKeyValidationStub
        $this->app->bind(ForeignKeyValidationStub::class, function ($app, $params) {
            return new ForeignKeyValidationStub(...$params);
        });

        // Bind the ForeignKeyInputStub
        $this->app->bind(ForeignKeyInputStub::class, function ($app, $params) {
            return (new ForeignKeyInputStubFactory(...$params))
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

        // Bind the InputsStubInteractor
        $this->app->bind(InputsStubInteractor::class, function ($app, $params) {
            return (new InputsStubInteractor(...$params))
                ->getStub();
        });

        // Bind the StubPlaceholderInteractor
        $this->app->bind(StubPlaceholderInteractor::class, function ($app, $params) {
            return new StubPlaceholderInteractor(...$params);
        });

        // Bind the InputStub
        $this->app->bind(InputStub::class, function ($app, $params) {
            return (new InputStubFactory(...$params))
                ->get();
        });

        // Bind the HasOneInput
        $this->app->bind(HasOneInput::class, function ($app, $params) {
            return new HasOneInput(...$params);
        });

        // Bind the ControllerStub
        $this->app->bind(ControllerStub::class, function ($app, $params) {
            return new ControllerStub(...$params);
        });

        // Bind the ModelStub
        $this->app->bind(ModelStub::class, function ($app, $params) {
            return new ModelStub(...$params);
        });

        // Bind the RequestStub
        $this->app->bind(RequestStub::class, function ($app, $params) {
            return new RequestStub(...$params);
        });

        // Bind the ViewStub
        $this->app->bind(ViewStub::class, function ($app, $params) {
            return new ViewStub(...$params);
        });

        // Bind the ColumnFactory
        $this->app->bind(ColumnFactory::class, function ($app, $params) {
            return new ColumnFactory(...$params);
        });

        // Bind the ForeignKeyInputStubFactory
        $this->app->bind(ForeignKeyInputStubFactory::class, function ($app, $params) {
            return new ForeignKeyInputStubFactory(...$params);
        });

        // Bind the ForeignKeyInputsStub
        $this->app->bind(ForeignKeyInputsStub::class, function ($app, $params) {
            return new ForeignKeyInputsStub(...$params);
        });

        // Bind the ColumnInputsStub
        $this->app->bind(ColumnInputsStub::class, function ($app, $params) {
            return new ColumnInputsStub(...$params);
        });

        // Bind the AttributeInteractor
        $this->app->bind(AttributeInteractor::class, function ($app, $params) {
            return new AttributeInteractor(...$params);
        });

        // Bind the Attribute
        $this->app->bind(Attribute::class, function ($app, $params) {
            return new Attribute(...$params);
        });

        // Bind the Disabled
        $this->app->bind(Disabled::class, function ($app, $params) {
            return new Disabled(...$params);
        });

        // Bind the Checked
        $this->app->bind(Checked::class, function ($app, $params) {
            return new Checked(...$params);
        });

        // Bind the Min
        $this->app->bind(Min::class, function ($app, $params) {
            return new Min(...$params);
        });

        // Bind the Value
        $this->app->bind(Value::class, function ($app, $params) {
            return new Value(...$params);
        });

        // Bind the AllAttributes
        $this->app->bind(AllAttributes::class, function ($app, $params) {
            return new AllAttributes(...$params);
        });
    }
}