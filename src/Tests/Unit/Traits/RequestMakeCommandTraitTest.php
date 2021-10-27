<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\RequestMakeCommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use stdClass;

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

    /**
     * A test to replace the rules within a stub.
     *
     * @return void
     */
    public function test_replace_rules()
    {
        $rules = ['rules'];
        $stub = 'stub';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules, $stub) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getNonIdRules')
                ->once()
                ->andReturn($rules);
            $mock->shouldReceive('updateStubWithRules')
                ->with($stub, $rules)
                ->once();
        });

        $result = $mock->replaceRules($stub);
        
        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
    }

    /**
     * A test getting the non-id rules within the rules.
     *
     * @return void
     */
    public function test_get_non_id_rules()
    {
        $rule1 = new stdClass();
        $rule1->name = 'name-1';

        $rule2 = new stdClass();
        $rule2->name = 'name-2';

        $ruleId = new stdClass();
        $ruleId->name = 'id';

        $rules = [
            $rule1,
            $ruleId,
            $rule2,
        ];

        $expectedResult= [
            $rule1,
            $rule2,
        ];

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getRules')
                ->once()
                ->andReturn($rules);
        });
        

        $result = $mock->getNonIdRules($rules);

        $this->assertSame($expectedResult, array_values($result), 'The rules array did not filter correctly to only include non-id columns.');
    }

    /**
     * A test to get the rules.
     *
     * @return void
     */
    public function test_get_rules()
    {
        $expectedResult = $rules = ['rules'];
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($rules) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('rules')
                ->once()
                ->andReturn($rules);
        });
        
        $result = $mock->getRules();

        $this->assertSame($expectedResult, $result, 'The rules are correct.');
    }
}