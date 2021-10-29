<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ControllerMakeCommandTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ControllerMakeCommandTraitTest extends TestCase
{
    use ControllerMakeCommandTrait, CommandTrait, InputTrait, TestTrait;

    /**
     * The inputs for the test.
     *
     * @var array
     */
    protected $inputs;

    public function setUp() : void
    {
        parent::setUp();
        $this->inputs = $this->getMockColumns();
    }

    /**
     * A test for the method calls with the buildClass method without a model.
     *
     * @return void
     */
    public function test_build_class_method_calls_without_model()
    {
        $name = 'name';
        $stubLocation = 'stub-location';
        $stub = $originalStub = 'stub';
        $resource = 'resource';
        $inputsString = 'inputsString';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $stubLocation, $name, $resource, $inputsString) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getStub')
                ->andReturn($stubLocation);
            $mock->shouldReceive('getControllerInputsString')
                ->with()
                ->andReturn($inputsString);
            $mock->shouldReceive('option')
                ->with('model')
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('replaceModel')
                ->never();
            $mock->shouldReceive('replaceNamespace')
                ->with($stub, $name)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('getResource')
                ->once()
                ->andReturn($resource);
            $mock->shouldReceive('replaceInStub')
                ->with($this->resourcePlaceholders, $resource, $stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceInStub')
                ->with($this->inputPlaceholders, $inputsString, $stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceClass')
                ->with($stub, $name)
                ->once()
                ->andReturn($mock);

            $filesMock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $stubLocation) {
                $mock->shouldAllowMockingProtectedMethods();
                $mock->shouldReceive('get')
                    ->with($stubLocation)
                    ->andReturn($stub);
            });
    
            $mock->files = $filesMock;
        });

        $mock->buildClass($name);

        $this->assertIsString($stub, 'The stub should be a string.');
        $this->assertNotEmpty($stub, 'The stub shouldn\'t be empty.');
        $this->assertSame($originalStub, $stub, 'The stub shouldn\'t be updated based on the mocks within this test.');
    }

    /**
     * A test for getting the resource.
     *
     * @return void
     */
    public function test_get_resource()
    {
        $name = 'controller-name-controller';
        $expectedResult = '-name-';
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->once()
                ->andReturn($name);
        });

        $result = $mock->getResource();

        $this->assertSame($expectedResult, $result, 'The resource is incorrect.');
    }

    /**
     * A test for replacing the model.
     *
     * @return void
     */
    public function test_replace_model()
    {
        $stub = 'stub';
        $inputs = $this->getMockColumns();
        $model = 'model';
        $modelClass = 'modelClass';
        $callArguments = [
            'name' => $modelClass,
            '--inputs' => $inputs,
        ];
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($model, $modelClass, $inputs, $stub, $callArguments) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getModel')
                ->once()
                ->andReturn($model);
            $mock->shouldReceive('parseModel')
                ->with($model)
                ->once()
                ->andReturn($modelClass);
            $mock->shouldReceive('getInputs')
                ->once()
                ->andReturn($inputs);
            $mock->shouldReceive('call')
                ->with('cruddy:model', $callArguments)
                ->once();
            $mock->shouldReceive('replaceInStub')
                ->with($this->modelPlaceholders, $modelClass, $stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceInStub')
                ->with($this->modelVariablePlaceholders, $modelClass, $stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceInStub')
                ->with($this->fullModelClassPlaceholders, $modelClass, $stub)
                ->once()
                ->andReturn($mock);
        });

        $result = $mock->replaceModel($stub);

        $this->assertIsObject($result, 'The result from replacing the model was not an object.');
        $this->assertInstanceOf(self::class, $result, 'The result of replacing the model did not return an instance of the correct class.');
    }

    /**
     * A test for getting the model.
     *
     * @return void
     */
    public function test_get_model()
    {
        $expectedResult = $model = 'model';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($model) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('option')
                ->with('model')
                ->once()
                ->andReturn($model);
        });
        
        $result = $mock->getModel();

        $this->assertSame($expectedResult, $result, 'The model is incorrect.');
    }

    /**
     * A test to get the default namespace.
     *
     * @return void
     */
    public function test_get_default_namespace()
    {
        $rootNamespace = 'rootNamespace';
        $expectedResult = $rootNamespace . '\Http\Controllers';

        $result = $this->getDefaultNamespace($rootNamespace);

        $this->assertSame($expectedResult, $result, 'The default namespace does not match.');
    }
}