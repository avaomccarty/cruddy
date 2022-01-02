<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input;

use Cruddy\Exceptions\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Cruddy\StubEditors\Inputs\Input\ControllerStubInputEditor;
use Cruddy\StubEditors\Inputs\Input\RequestStubInputEditor;
use Cruddy\StubEditors\Inputs\Input\StubInputEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ViewStubInputEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Orchestra\Testbench\TestCase;

class StubInputEditorFactoryTest extends TestCase
{
    /**
     * A test to get the controller stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_controller_class()
    {
        $stub = 'stub';
        $result = StubInputEditorFactory::get(new ColumnDefinition([]), 'controller', true, $stub);

        $this->assertInstanceOf(ControllerStubInputEditor::class, $result);
    }

    /**
     * A test to get the request stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_request_class()
    {
        $stub = 'stub';
        $result = StubInputEditorFactory::get(new ColumnDefinition([]), 'request', true, $stub);

        $this->assertInstanceOf(RequestStubInputEditor::class, $result);
    }

    /**
     * A test to get the view stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_view_class()
    {
        $stub = 'stub';
        $result = StubInputEditorFactory::get(new ColumnDefinition([]), 'view', true, $stub);

        $this->assertInstanceOf(ViewStubInputEditor::class, $result);
    }

    /**
     * A test to get an unknown stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_unknown_class()
    {
        $this->expectException(UnknownStubInputEditorType::class);
        
        StubInputEditorFactory::get(new ColumnDefinition([]), 'unknown-input-stub-editor');
    }
}