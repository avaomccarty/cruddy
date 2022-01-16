<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\StubEditors\ControllerStubEditor;
use Cruddy\StubEditors\Inputs\Input\Columns\ControllerColumnInputStubEditor;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Cruddy\StubEditors\ModelStubEditor;
use Cruddy\StubEditors\StubEditor;
use Cruddy\Tests\TestTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;

class ControllerMakeCommandTest extends TestCase
{
    use TestTrait;

    /**
     * A test to make a non-API resource controller file.
     *
     * @return void
     */
    public function test_make_resource_controller_file_is_not_api()
    {
        $name = 'Foo';
        $isResource = true;
        $isApi = false;
        $inputs = $this->getMockColumns();
        $commands = $this->getMockCommands();

        $expectedStubLocation = base_path() . '/stubs/controller.stub';
        $stubLocation = dirname(dirname(__DIR__)) . '/Commands/stubs/controller.stub';
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = base_path() . '/app/Http/Controllers';
        $expectedBladeFile = File::get(dirname(__DIR__) . '/stubs/controllers/expectedFile.stub');
        $expectedBladeFileName = $expectedBladeFileLocation . '/name.php';

        foreach ($inputs as $input) {
            $stubInputEditor = new ControllerColumnInputStubEditor($input);
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'controller', '', false])
                ->once()
                ->andReturn($stubInputEditor);
        }

        foreach ($commands as $command) {
            $modelRelationship = new ModelRelationship($command);
            App::shouldReceive('make')
                ->with(ModelRelationship::class, [$command])
                ->once()
                ->andReturn($modelRelationship);
        }

        $stubEditor = new ControllerStubEditor();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['controller'])
            ->once()
            ->andReturn($stubEditor);

        $stubEditor = new ModelStubEditor();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['model'])
            ->once()
            ->andReturn($stubEditor);

        $stubInputsEditor = new StubInputsEditor($inputs, 'controller');
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'controller'])
            ->once()
            ->andReturn($stubInputsEditor);

        App::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(2)
            ->andReturn('stubs');

        Config::partialMock();

        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('isDirectory')
            ->with($expectedBladeFileLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('put')
            ->with($expectedBladeFileName, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:controller', [
            'name' => $name . 'Controller',
            '--resource' => $isResource,
            '--model' => $name,
            '--api' => $isApi,
            '--inputs' => $inputs,
            '--commands' => $commands,
            '--force' => true,
        ]);
    }

    /**
     * A test to make an API resource controller file.
     *
     * @return void
     */
    public function test_make_resource_controller_file_is_api()
    {
        $name = 'Foo';
        $isResource = true;
        $isApi = true;
        $inputs = $this->getMockColumns();
        $commands = $this->getMockCommands();

        $expectedStubLocation = base_path() . '/stubs/controller.api.stub';
        $stubLocation = dirname(dirname(__DIR__)) . '/Commands/stubs/controller.api.stub';
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = base_path() . '/app/Http/Controllers';
        $expectedBladeFile = File::get(dirname(__DIR__) . '/stubs/controllers/expectedFile.api.stub');
        $expectedBladeFileName = $expectedBladeFileLocation . '/name.php';

        foreach ($inputs as $input) {
            $stubInputEditor = new ControllerColumnInputStubEditor($input);
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'controller', '', false])
                ->once()
                ->andReturn($stubInputEditor);
        }

        foreach ($commands as $command) {
            $modelRelationship = new ModelRelationship($command);
            App::shouldReceive('make')
                ->with(ModelRelationship::class, [$command])
                ->once()
                ->andReturn($modelRelationship);
        }

        $stubEditor = new ControllerStubEditor();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['controller'])
            ->once()
            ->andReturn($stubEditor);

        $stubEditor = new ModelStubEditor();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['model'])
            ->once()
            ->andReturn($stubEditor);

        $stubInputsEditor = new StubInputsEditor($inputs, 'controller');
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'controller'])
            ->once()
            ->andReturn($stubInputsEditor);

        App::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(2)
            ->andReturn('stubs');

        Config::partialMock();

        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('isDirectory')
            ->with($expectedBladeFileLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('put')
            ->with($expectedBladeFileName, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:controller', [
            'name' => $name . 'Controller',
            '--resource' => $isResource,
            '--model' => $name,
            '--api' => $isApi,
            '--inputs' => $inputs,
            '--commands' => $commands,
            '--force' => true,
        ]);
    }

    /**
     * A test to make a resource controller file without a model.
     *
     * @return void
     */
    public function test_make_resource_controller_file_without_a_model()
    {
        $name = 'Foo';
        $isResource = true;
        $isApi = true;
        $inputs = $this->getMockColumns();
        $commands = $this->getMockCommands();

        $expectedStubLocation = base_path() . '/stubs/controller.api.stub';
        $stubLocation = dirname(dirname(__DIR__)) . '/Commands/stubs/controller.api.stub';
        $stub = File::get($stubLocation);
        $expectedBladeFileLocation = base_path() . '/app/Http/Controllers';
        $expectedBladeFile = File::get(dirname(__DIR__) . '/stubs/controllers/expectedFile.api.stub');
        $expectedBladeFileName = $expectedBladeFileLocation . '/name.php';

        foreach ($inputs as $input) {
            $stubInputEditor = new ControllerColumnInputStubEditor($input);
            App::shouldReceive('make')
                ->with(StubInputEditor::class, [$input, 'controller', '', false])
                ->once()
                ->andReturn($stubInputEditor);
        }

        $stubEditor = new ControllerStubEditor();
        App::shouldReceive('make')
            ->with(StubEditor::class, ['controller'])
            ->once()
            ->andReturn($stubEditor);

        $stubInputsEditor = new StubInputsEditor($inputs, 'controller');
        App::shouldReceive('make')
            ->with(StubInputsEditor::class, [$inputs, 'controller'])
            ->once()
            ->andReturn($stubInputsEditor);

        App::partialMock();

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->times(1)
            ->andReturn('stubs');

        Config::partialMock();

        File::shouldReceive('exists')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($expectedStubLocation)
            ->once()
            ->andReturn($stub);

        File::shouldReceive('isDirectory')
            ->with($expectedBladeFileLocation)
            ->once()
            ->andReturn(true);

        File::shouldReceive('put')
            ->with($expectedBladeFileName, $expectedBladeFile)
            ->once()
            ->andReturn(true);

        File::partialMock();

        $this->artisan('cruddy:controller', [
            'name' => $name . 'Controller',
            '--resource' => $isResource,
            '--api' => $isApi,
            '--inputs' => $inputs,
            '--commands' => $commands,
            '--force' => true,
        ]);
    }
}