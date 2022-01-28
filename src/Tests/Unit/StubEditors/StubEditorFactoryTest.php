<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\Exceptions\UnknownStubEditorType;
use Cruddy\StubEditors\ControllerStub;
use Cruddy\StubEditors\ModelStub;
use Cruddy\StubEditors\RequestStub;
use Cruddy\StubEditors\StubEditorFactory;
use Cruddy\StubEditors\ViewStub;
use Orchestra\Testbench\TestCase;

class StubEditorFactoryTest extends TestCase
{
    /**
     * A test to get the controller stub editor.
     *
     * @return void
     */
    public function test_get_controller_stub_editor()
    {
        $stubEditor = 'controller';
        
        $result = (new StubEditorFactory($stubEditor))
            ->get();

        $this->assertInstanceOf(ControllerStub::class, $result);
    }

    /**
     * A test to get the model stub editor.
     *
     * @return void
     */
    public function test_get_model_stub_editor()
    {
        $stubEditor = 'model';
        
        $result = (new StubEditorFactory($stubEditor))
            ->get();

        $this->assertInstanceOf(ModelStub::class, $result);
    }

    /**
     * A test to get the request stub editor.
     *
     * @return void
     */
    public function test_get_request_stub_editor()
    {
        $stubEditor = 'request';
        
        $result = (new StubEditorFactory($stubEditor))
            ->get();

        $this->assertInstanceOf(RequestStub::class, $result);
    }

    /**
     * A test to get the view stub editor.
     *
     * @return void
     */
    public function test_get_view_stub_editor()
    {
        $stubEditor = 'view';
        
        $result = (new StubEditorFactory($stubEditor))
            ->get();

        $this->assertInstanceOf(ViewStub::class, $result);
    }

    /**
     * A test to get the default from the factory.
     *
     * @return void
     */
    public function test_get_default_stub_editor()
    {
        $result = (new StubEditorFactory())
            ->get();

        $this->assertInstanceOf(ControllerStub::class, $result);
    }

    /**
     * A test to get an unknown type within the factory.
     *
     * @return void
     */
    public function test_get_unknown_stub_editor()
    {
        $this->expectException(UnknownStubEditorType::class);

        (new StubEditorFactory('invalid-or-unknown-type'))
            ->get();
    }
}