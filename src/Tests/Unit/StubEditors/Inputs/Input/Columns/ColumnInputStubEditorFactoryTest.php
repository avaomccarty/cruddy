<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Cruddy\StubEditors\Inputs\Input\Columns\ControllerStubInputEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\RequestStubInputEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\Columns\ViewStubInputEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Orchestra\Testbench\TestCase;

class ColumnInputStubEditorFactoryTest extends TestCase
{
    /**
     * A test to get the controller stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_controller_class()
    {
        $stub = 'stub';
        $result = (new ColumnInputStubEditorFactory(new ColumnDefinition(), 'controller', $stub))
            ->get();

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
        $result = (new ColumnInputStubEditorFactory(new ColumnDefinition(), 'request', $stub))
            ->get();

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
        $result = (new ColumnInputStubEditorFactory(new ColumnDefinition(), 'view', $stub))
            ->get();

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
        
        (new ColumnInputStubEditorFactory(new ColumnDefinition(), 'unknown-input-stub-editor'))
            ->get();
    }
}