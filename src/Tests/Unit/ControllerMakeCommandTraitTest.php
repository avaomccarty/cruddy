<?php

namespace Cruddy\Tests\Unit;

use Cruddy\ServiceProvider;
use Cruddy\Tests\TestTrait;
use Cruddy\Traits\ControllerMakeCommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ControllerMakeCommandTraitTest extends TestCase
{
    use ControllerMakeCommandTrait, TestTrait;

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

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $stubLocation, $name) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('option')
                ->with('model')
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('replaceNamespace')
                ->with($stub, $name)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceResource')
                ->with($stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceInputs')
                ->with($stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceClass')
                ->with($stub, $name)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('getStub')
                ->andReturn($stubLocation);

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
        $inputs = $this->inputs;
        $model = 'model';
        $modelClass = 'modelClass';
        $callArguments = [
            'name' => $modelClass,
            '--inputs' => $inputs,
        ];
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($model, $modelClass, $inputs, $stub, $callArguments) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('option')
                ->with('model')
                ->once()
                ->andReturn($model);
            $mock->shouldReceive('parseModel')
                ->with($model)
                ->once()
                ->andReturn($modelClass);
            $mock->shouldReceive('option')
                ->with('inputs')
                ->once()
                ->andReturn($inputs);
            $mock->shouldReceive('call')
                ->with('cruddy:model', $callArguments)
                ->once();
            $mock->shouldReceive('replaceModelPlaceholder')
                ->with($modelClass, $stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceModelVariablePlaceholders')
                ->with($modelClass, $stub)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceFullModelPlaceholders')
                ->with($modelClass, $stub)
                ->once()
                ->andReturn($mock);
        });

        $result = $mock->replaceModel($stub);

        $this->assertIsObject($result, 'The result from replacing the model was not an object.');
        $this->assertInstanceOf(self::class, $result, 'The result of replacing the model did not return an instance of the correct class.');
    }
}