<?php

namespace Cruddy\Tests\Unit\Traits;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\Stubs\VariableTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Cruddy\Traits\VueViewMakeCommandTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VueViewMakeCommandTraitTest extends TestCase
{
    use VueViewMakeCommandTrait, VueTrait, VariableTrait, TestTrait;

    /**
     * A test to get the default namespace.
     *
     * @return void
     */
    public function test_get_default_namespace()
    {
        $rootNamespace = 'rootNamespace';
        $expectedResult = $rootNamespace . '\resources\views';

        $result = $this->getDefaultNamespace($rootNamespace);

        $this->assertSame($expectedResult, $result, 'The default namespace does not match.');
    }

    /**
     * A test for getting the props string for index.
     *
     * @return void
     */
    public function test_get_props_string_for_index()
    {
        $table = 'Table';
        $prop = 'table';
        $type = 'index';
        $expectedResult = ' :prop-items="{{ json_encode($' . $prop . '->toArray()[\'data\']) }}"';
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($table, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getTableName')
                ->once()
                ->andReturn($table);
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->getPropsString();

        $this->assertSame($expectedResult, $result, 'The default namespace does not match.');
    }

    /**
     * A test for getting the props string for show.
     *
     * @return void
     */
    public function test_get_props_string_for_show()
    {
        $table = 'Tables';
        $prop = 'table';
        $type = 'show';
        $expectedResult = ' :prop-item="{{ $' . $prop . ' }}"';
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($table, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getTableName')
                ->once()
                ->andReturn($table);
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->getPropsString();

        $this->assertSame($expectedResult, $result, 'The default namespace does not match.');
    }

    /**
     * A test for getting the props string for edit.
     *
     * @return void
     */
    public function test_get_props_string_for_edit()
    {
        $table = 'Tables';
        $prop = 'table';
        $type = 'edit';
        $expectedResult = ' :prop-item="{{ $' . $prop . ' }}"';
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($table, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getTableName')
                ->once()
                ->andReturn($table);
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->getPropsString();

        $this->assertSame($expectedResult, $result, 'The default namespace does not match.');
    }

    /**
     * A test for getting the props string for create.
     *
     * @return void
     */
    public function test_get_props_string_for_create()
    {
        $table = 'table';
        $type = 'create';
        $expectedResult = '';
        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($table, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getTableName')
                ->once()
                ->andReturn($table);
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        
        $result = $mock->getPropsString();

        $this->assertSame($expectedResult, $result, 'The default namespace does not match.');
    }

    /**
     * A test to replace variable name for index.
     *
     * @return void
     */
    public function test_replace_variable_name_for_index()
    {
        $baseStub = 'stub-';
        $expectedStub = $stub = $baseStub;
        foreach ($this->valuePlaceholders as $stubPlaceholder) {
            $stub .= $stubPlaceholder;
        }

        $type = 'index';
        $className = 'className';
        $originalStub = $stub;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $type, $className) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('getClassName')
                ->once()
                ->andReturn($className);
        });
     
        $result = $mock->replaceVariableName($stub);

        foreach ($this->valuePlaceholders as $stubPlaceholder) {
            $expectedStub .= strtolower($className) . 's';
        }

        $this->assertIsObject($result, 'The result should be an object.');
        $this->assertInstanceOf(self::class, $result, 'The result has an incorrect instance type.');
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The variables were not replaced correctly within the stub.');
    }

    /**
     * A test to replace variable name for non-index file types.
     *
     * @return void
     */
    public function test_replace_variable_name_for_not_index()
    {
        $type = 'create';
        $className = 'className';
        $originalStub = $expectedStub = $stub = 'stub-';
        foreach ($this->valuePlaceholders as $stubPlaceholder) {
            $stub .= $stubPlaceholder;
            $expectedStub .= strtolower($className);
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($stub, $type, $className) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('getClassName')
                ->once()
                ->andReturn($className);
        });
     
        $result = $mock->replaceVariableName($stub);

        $this->assertNotSame($originalStub, $stub, 'The stub was not updated.');
        $this->assertIsObject($result, 'The result should be an object.');
        $this->assertInstanceOf(self::class, $result, 'The result has an incorrect instance type.');
        $this->assertSame($expectedStub, $stub, 'The variables were not replaced correctly within the stub.');
    }

    /**
     * A test to replace the component name variable.
     *
     * @return void
     */
    public function test_replace_component_name_variable()
    {
        $name = 'nameForTest';
        $kebabName = 'name-for-test';
        $type = 'type';
        $stub = 'stub-';
        $componentName  = $kebabName . '-' . $type;

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type, $componentName, $stub) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->once()
                ->andReturn($name);
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
            $mock->shouldReceive('replaceStubComponentNamePlaceholders')
                ->with($componentName, $stub)
                ->once();
        });

        $result = $mock->replaceComponentNameVariable($stub);

        $this->assertIsObject($result, 'The result should be an object.');
        $this->assertInstanceOf(self::class, $result, 'The result has an incorrect instance type.');
    }
}