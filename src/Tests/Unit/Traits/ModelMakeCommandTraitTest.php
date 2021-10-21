<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\ModelMakeCommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ModelMakeCommandTraitTest extends TestCase
{
    use ModelMakeCommandTrait, TestTrait;

    /**
     * A test to get the stub.
     *
     * @return void
     */
    public function test_get_stub()
    {
        $stubLocation = 'stub-location';
        $expectedResolvedStubLocation = $stubLocation . '/model.stub';
        $expectedOutput = 'output' . $stubLocation . '/model.stub';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stubLocation, $expectedResolvedStubLocation, $expectedOutput) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getStubsLocation')
                ->andReturn($stubLocation);
            $mock->shouldReceive('resolveStubPath')
                ->with($expectedResolvedStubLocation)
                ->andReturn($expectedOutput);
        });

        $result = $mock->getStub();

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The value shouldn\'t be empty.');
        $this->assertSame($expectedOutput, $result, 'The value is incorrect.');
    }

    /**
     * A test for the method calls with the buildClass method.
     *
     * @return void
     */
    public function test_build_class_method_calls()
    {
        $name = 'name';
        $stubLocation = 'stub-location';
        $stub = $originalStub = 'stub';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $stubLocation, $name) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('replaceNamespace')
                ->with($stub, $name)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('replaceModelInputs')
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
     * A test 
     *
     * @return void
     */
    public function test_replace_model_inputs()
    {
        $stub ='stub-';
        $inputs = $this->getMockColumns();
        $inputsString = '';

        foreach ($inputs as $input) {
            $inputsString .= $input->name;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($inputs, $inputsString, $stub) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getInputs')
                ->once()
                ->andReturn($inputs);
            
            foreach ($inputs as $input) {
                $mock->shouldReceive('getInputString')
                    ->with($input)
                    ->once()
                    ->andReturn($input->name);
            }

            $mock->shouldReceive('removeEndOfLineFormatting')
                ->with($inputsString)
                ->once();

            $mock->shouldReceive('replaceModelPlaceholders')
                ->with($inputsString, $stub)
                ->once();
        });

        $result = $mock->replaceModelInputs($stub);
        
        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
    }
}