<?php

namespace Cruddy\Tests\Unit\StubEditors;

use Cruddy\StubEditors\InputsStubEditor;
use Cruddy\Tests\TestTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ColumnsStubEditorTest extends TestCase
{
    // use TestTrait;

    // /**
    //  * A test to get the stub file.
    //  *
    //  * @return void
    //  */
    // public function test_get_stub_file()
    // {
    //     $column = new ColumnDefinition([
    //         'type' => 'submit'
    //     ]);
    //     $stub = $expectedResult = 'stub';

    //     Config::shouldReceive('get')
    //         ->with('cruddy.stubs_folder')
    //         ->andReturn('stubs');
    //     Config::shouldReceive('get')
    //         ->with('cruddy.frontend_scaffolding')
    //         ->andReturn('default');
    //     Config::shouldReceive('get')
    //         ->with('cruddy.input_defaults')
    //         ->andReturn([]);

    //     Config::partialMock();

    //     $inputStubEditorMock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub) {
    //         $mock->shouldAllowMockingProtectedMethods();
    //         $mock->shouldReceive('getStubFile')
    //             ->once()
    //             ->andReturn($stub);
    //     });
        
    //     App::shouldReceive('make')
    //         ->with(InputsStubEditor::class, [$column])
    //         ->once()
    //         ->andReturn($inputStubEditorMock);
        
    //     App::partialMock();

    //     $result = (new ColumnsStubEditor([]))
    //         ->getStubFile();

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the input string with a view submit input included.
    //  *
    //  * @return void
    //  */
    // public function test_get_input_string_with_view_submit_input()
    // {
    //     $needsSubmit = true;
    //     $columns = $this->getMockColumns();
    //     $inputString = 'inputString-';
    //     $expectedResult = $stub = 'stub-';

    //     Config::shouldReceive('get')
    //         ->with('cruddy.stubs_folder')
    //         ->andReturn('stubs');
    //     Config::shouldReceive('get')
    //         ->with('cruddy.frontend_scaffolding')
    //         ->andReturn('default');

    //     Config::partialMock();
        
    //     foreach ($columns as $column) {
    //         $expectedResult = $inputString . $expectedResult;
    //         $inputStubEditorMock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputString, $stub) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getStubFile')
    //                 ->andReturn($stub);
    //             $mock->shouldReceive('getInputString')
    //                 ->once()
    //                 ->andReturn($inputString);
    //         });

    //         App::shouldReceive('make')
    //             ->with(InputsStubEditor::class, [$column])
    //             ->once()
    //             ->andReturn($inputStubEditorMock);
    //     }

    //     App::partialMock();

    //     $result = (new ColumnsStubEditor($columns))
    //         ->getViewInputsString($needsSubmit);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the input string without a view submit input included.
    //  *
    //  * @return void
    //  */
    // public function test_get_input_string_without_view_submit_input()
    // {
    //     $needsSubmit = false;
    //     $columns = $this->getMockColumns();
    //     $inputString = 'inputString-';
    //     $expectedResult = '';

    //     Config::shouldReceive('get')
    //         ->with('cruddy.stubs_folder')
    //         ->andReturn('stubs');
    //     Config::shouldReceive('get')
    //         ->with('cruddy.frontend_scaffolding')
    //         ->andReturn('default');

    //     Config::partialMock();

    //     foreach ($columns as $column) {
    //         $expectedResult .= $inputString;
    //         $inputStubEditorMock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputString) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getStubFile')
    //                 ->never();
    //             $mock->shouldReceive('getInputString')
    //                 ->andReturn($inputString);
    //         });

    //         App::shouldReceive('make')
    //             ->with(InputsStubEditor::class, [$column])
    //             ->once()
    //             ->andReturn($inputStubEditorMock);
    //     }

    //     App::partialMock();

    //     $result = (new ColumnsStubEditor($columns))
    //         ->getViewInputsString($needsSubmit);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the input string with a controller submit input included.
    //  *
    //  * @return void
    //  */
    // public function test_get_input_string_with_controller_submit_input()
    // {
    //     $needsSubmit = true;
    //     $columns = $this->getMockColumns();
    //     $inputString = 'inputString-';
    //     $expectedResult = $stub = 'stub-';

    //     Config::shouldReceive('get')
    //         ->with('cruddy.stubs_folder')
    //         ->andReturn('stubs');
    //     Config::shouldReceive('get')
    //         ->with('cruddy.frontend_scaffolding')
    //         ->andReturn('default');

    //     Config::partialMock();
        
    //     foreach ($columns as $column) {
    //         $expectedResult = $inputString . $expectedResult;
    //         $inputStubEditorMock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputString, $stub) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getStubFile')
    //                 ->andReturn($stub);
    //             $mock->shouldReceive('getInputString')
    //                 ->once()
    //                 ->andReturn($inputString);
    //         });

    //         App::shouldReceive('make')
    //             ->with(InputsStubEditor::class, [$column])
    //             ->once()
    //             ->andReturn($inputStubEditorMock);
    //     }

    //     App::partialMock();

    //     $result = (new ColumnsStubEditor($columns))
    //         ->getControllerInputsString($needsSubmit);

    //     $this->assertSame($expectedResult, $result);
    // }

    // /**
    //  * A test to get the input string without a controller submit input included.
    //  *
    //  * @return void
    //  */
    // public function test_get_input_string_without_controller_submit_input()
    // {
    //     $needsSubmit = false;
    //     $columns = $this->getMockColumns();
    //     $inputString = 'inputString-';
    //     $expectedResult = '';

    //     Config::shouldReceive('get')
    //         ->with('cruddy.stubs_folder')
    //         ->andReturn('stubs');
    //     Config::shouldReceive('get')
    //         ->with('cruddy.frontend_scaffolding')
    //         ->andReturn('default');

    //     Config::partialMock();

    //     foreach ($columns as $column) {
    //         $expectedResult .= $inputString;
    //         $inputStubEditorMock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputString) {
    //             $mock->shouldAllowMockingProtectedMethods();
    //             $mock->shouldReceive('getStubFile')
    //                 ->never();
    //             $mock->shouldReceive('getInputString')
    //                 ->andReturn($inputString);
    //         });

    //         App::shouldReceive('make')
    //             ->with(InputsStubEditor::class, [$column])
    //             ->once()
    //             ->andReturn($inputStubEditorMock);
    //     }

    //     App::partialMock();

    //     $result = (new ColumnsStubEditor($columns))
    //         ->getControllerInputsString($needsSubmit);

    //     $this->assertSame($expectedResult, $result);
    // }
}