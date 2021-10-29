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
     * The inputs for the test.
     *
     * @var array
     */
    protected $inputs = [];

    public function setUp() : void
    {
        parent::setUp();
        $this->inputs = $this->getMockColumns();
    }

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
        $modelInputs = 'modelInputs';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $stubLocation, $name, $modelInputs) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('replaceNamespace')
                ->with($stub, $name)
                ->once()
                ->andReturn($mock);
            $mock->shouldReceive('getModelInputs')
                ->once()
                ->andReturn($modelInputs);
            $mock->shouldReceive('replaceInStub')
                ->with($this->modelPlaceholders, $modelInputs, $stub)
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
}