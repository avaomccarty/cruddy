<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\Exceptions\UnknownStubEditorType;
use Cruddy\StubEditors\ControllerStubEditor;
use Cruddy\StubEditors\ModelStubEditor;
use Cruddy\StubEditors\RequestStubEditor;
use Cruddy\StubEditors\StubEditorFactory;
use Cruddy\StubEditors\ViewStubEditor;
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
        
        $result = StubEditorFactory::get($stubEditor);

        $this->assertInstanceOf(ControllerStubEditor::class, $result);
    }

    /**
     * A test to get the model stub editor.
     *
     * @return void
     */
    public function test_get_model_stub_editor()
    {
        $stubEditor = 'model';
        
        $result = StubEditorFactory::get($stubEditor);

        $this->assertInstanceOf(ModelStubEditor::class, $result);
    }

    /**
     * A test to get the request stub editor.
     *
     * @return void
     */
    public function test_get_request_stub_editor()
    {
        $stubEditor = 'request';
        
        $result = StubEditorFactory::get($stubEditor);

        $this->assertInstanceOf(RequestStubEditor::class, $result);
    }

    /**
     * A test to get the view stub editor.
     *
     * @return void
     */
    public function test_get_view_stub_editor()
    {
        $stubEditor = 'view';
        
        $result = StubEditorFactory::get($stubEditor);

        $this->assertInstanceOf(ViewStubEditor::class, $result);
    }

    /**
     * A test to get the default from the factory.
     *
     * @return void
     */
    public function test_get_default_stub_editor()
    {
        $result = StubEditorFactory::get();

        $this->assertInstanceOf(ControllerStubEditor::class, $result);
    }

    /**
     * A test to get an unknown type within the factory.
     *
     * @return void
     */
    public function test_get_unknown_stub_editor()
    {
        $this->expectException(UnknownStubEditorType::class);

        StubEditorFactory::get('invalid-or-unknown-type');
    }
}