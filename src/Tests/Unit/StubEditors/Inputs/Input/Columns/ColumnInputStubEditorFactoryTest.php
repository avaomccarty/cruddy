<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Cruddy\StubEditors\Inputs\Input\Columns\ControllerColumnInputStubEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\RequestColumnInputStubEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\Columns\ViewColumnInputStubEditor;
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

        $this->assertInstanceOf(ControllerColumnInputStubEditor::class, $result);
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

        $this->assertInstanceOf(RequestColumnInputStubEditor::class, $result);
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

        $this->assertInstanceOf(ViewColumnInputStubEditor::class, $result);
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