<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input;

use Cruddy\StubEditors\Inputs\Input\ControllerStubInputEditor;
use Cruddy\StubEditors\Inputs\Input\RequestStubInputEditor;
use Cruddy\StubEditors\Inputs\Input\StubInputEditor;
use Cruddy\StubEditors\Inputs\Input\ViewStubInputEditor;
use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class StubInputsEditorTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->columns = [
            new ColumnDefinition([
                'name' => 'name-1',
                'type' => 'string',
            ]),
            new ColumnDefinition([
                'name' => 'name-2',
                'type' => 'integer',
            ]),
        ];
        $this->stub = 'stub';
        $this->type = 'index';
        $this->name = 'name';
    }
    
    /**
     * A test to get the input string for a controller file.
     *
     * @return void
     */
    public function test_get_controller_input_string()
    {
        $expectedResult = "'name-1' => \$request->name-1,\n\t\t\t\t'name-2' => \$request->name-2,";
        $fileType = 'controller';
        $stubInputsEditor = new StubInputsEditor($this->columns, $fileType, $this->stub);

        foreach ($this->columns as $column) {
            $stubInputEditor = new ControllerStubInputEditor($column, $this->stub);
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$column, $fileType, $this->stub, false])
                ->once()
                ->andReturn($stubInputEditor);
        }
        $result = $stubInputsEditor->getInputString($this->type, $this->name);

        $this->assertSame($expectedResult, $result);
    }
    
    /**
     * A test to get the input string for a request file.
     *
     * @return void
     */
    public function test_get_request_input_string()
    {
        $expectedResult = '';
        $fileType = 'request';
        $stubInputsEditor = new StubInputsEditor($this->columns, $fileType, $this->stub);

        foreach ($this->columns as $column) {
            $stubInputEditor = new RequestStubInputEditor($column, $this->stub);
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$column, $fileType, $this->stub, false])
                ->once()
                ->andReturn($stubInputEditor);
        }

        $expectedResult .= "'name-1' => 'text',\n\t\t\t'name-2' => 'number',";

        Config::shouldReceive('get')
            ->with('cruddy.validation_defaults')
            ->times(2)
            ->andReturn([
                'string' => 'text',
                'integer' => 'number',
            ]);

        $result = $stubInputsEditor->getInputString($this->type, $this->name);

        $this->assertSame($expectedResult, $result);
    }
    
    /**
     * A test to get the input string for a view file.
     *
     * @return void
     */
    public function test_get_view_input_string()
    {
        $expectedResult = "$this->stub\n\t\t\t\t$this->stub";
        $fileType = 'view';
        $stubInputsEditor = new StubInputsEditor($this->columns, $fileType, $this->stub);

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn('stubs-folder');
        Config::shouldReceive('get')
            ->with('cruddy.frontend_scaffolding')
            ->andReturn('default');
        Config::shouldReceive('get')
            ->with('cruddy.input_defaults')
            ->andReturn([]);
        File::shouldReceive('exists')
            ->andReturn(true);
        File::shouldReceive('get')
            ->andReturn($this->stub);

        foreach ($this->columns as $column) {
            $stubInputEditor = new ViewStubInputEditor($column, $this->stub);
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$column, $fileType, $this->stub, false])
                ->once()
                ->andReturn($stubInputEditor);
        }

        $result = $stubInputsEditor->getInputString($this->type, $this->name);

        $this->assertSame($expectedResult, $result);
    }
}