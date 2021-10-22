<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\RequestMakeCommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class RequestMakeCommandTraitTest extends TestCase
{
    use RequestMakeCommandTrait, TestTrait;

    /**
     * A test to get the default namespace.
     *
     * @return void
     */
    public function test_get_default_namespace()
    {
        $rootNamespace = 'rootNamespace';
        $expectedResult = $rootNamespace . '\Http\Requests';
        $result = $this->getDefaultNamespace($rootNamespace);

        $this->assertIsString($result, 'The type should be a string.');
        $this->assertNotEmpty($result, 'The table name value should be empty.');
        $this->assertSame($expectedResult, $result, 'The default namespace is incorrect.');
    }

    /**
     * A test to get the name input.
     *
     * @return void
     */
    public function test_get_name_input()
    {
        $type = 'type';
        $name = 'name';
        $studlySingularType = 'studlySingularType';
        $studlySingularName = 'studlySingularName';
        $expectedResult = $studlySingularType . $studlySingularName;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type, $name, $studlySingularType, $studlySingularName) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('argument')
                ->with('name')
                ->once()
                ->andReturn($name);
            $mock->shouldReceive('getStudlySingular')
                ->with($type)
                ->once()
                ->andReturn($studlySingularType);
            $mock->shouldReceive('getStudlySingular')
                ->with($name)
                ->once()
                ->andReturn($studlySingularName);
        });

        $result = $mock->getNameInput();

        $this->assertSame($expectedResult, $result, 'The name input does not match.');
    }

    /**
     * A test to get the stub.
     *
     * @return void
     */
    public function test_get_stub()
    {
        $stubLocation = 'stubLocation';
        $stubPath = $stubLocation . '/request.stub';
        $stub = 'stub';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stubLocation, $stubPath, $stub) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getStubsLocation')
                ->once()
                ->andReturn($stubLocation);
            $mock->shouldReceive('resolveStubPath')
                ->with($stubPath)
                ->once()
                ->andReturn($stub);
        });

        $result = $mock->getStub();

        $this->assertSame($stub, $result, 'The stub does not match.');
        
    }
}