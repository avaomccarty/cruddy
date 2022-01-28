<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\UnknownStubInputEditorType;
use Cruddy\StubEditors\Inputs\Input\Columns\ControllerColumnInputStub;
use Cruddy\StubEditors\Inputs\Input\Columns\RequestColumnInputStub;
use Cruddy\StubEditors\Inputs\Input\Columns\ColumnInputStubFactory;
use Cruddy\StubEditors\Inputs\Input\Columns\ViewColumnInputStub;
use Illuminate\Database\Schema\ColumnDefinition;
use Orchestra\Testbench\TestCase;

class ColumnInputStubFactoryTest extends TestCase
{
    /**
     * A test to get the controller stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_controller_class()
    {
        $stub = 'stub';
        $result = (new ColumnInputStubFactory(new ColumnDefinition(), 'controller', $stub))
            ->get();

        $this->assertInstanceOf(ControllerColumnInputStub::class, $result);
    }

    /**
     * A test to get the request stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_request_class()
    {
        $stub = 'stub';
        $result = (new ColumnInputStubFactory(new ColumnDefinition(), 'request', $stub))
            ->get();

        $this->assertInstanceOf(RequestColumnInputStub::class, $result);
    }

    /**
     * A test to get the view stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_view_class()
    {
        $stub = 'stub';
        $result = (new ColumnInputStubFactory(new ColumnDefinition(), 'view', $stub))
            ->get();

        $this->assertInstanceOf(ViewColumnInputStub::class, $result);
    }

    /**
     * A test to get an unknown stub input editor.
     *
     * @return void
     */
    public function test_get_stub_input_editor_for_unknown_class()
    {
        $this->expectException(UnknownStubInputEditorType::class);
        
        (new ColumnInputStubFactory(new ColumnDefinition(), 'unknown-input-stub-editor'))
            ->get();
    }
}