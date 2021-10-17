<?php

namespace Cruddy\Tests\Unit;

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
        foreach ($this->stubValuePlaceholders as $stubPlaceholder) {
            $stub .= $stubPlaceholder;
        }

        $type = 'index';
        $className = 'className';

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

        foreach ($this->stubValuePlaceholders as $stubPlaceholder) {
            $expectedStub .= strtolower($className) . 's';
        }

        $this->assertTrue(count($this->stubValuePlaceholders) > 0, 'The test requires having variables to replace within the stub.');
        $this->assertIsObject($result, 'The result should be an object.');
        $this->assertInstanceOf(self::class, $result, 'The result has an incorrect instance type.');
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
        foreach ($this->stubValuePlaceholders as $stubPlaceholder) {
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
        $originalStub = $expectedStub = $stub = 'stub-';

        foreach ($this->stubComponentNamePlaceholders as $stubPlaceholder) {
            $stub .= $stubPlaceholder;
            $expectedStub .= $kebabName . '-' . $type;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('argument')
                ->with('name')
                ->once()
                ->andReturn($name);
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });

        $result = $mock->replaceComponentNameVariable($stub);

        $this->assertNotSame($originalStub, $stub, 'The stub was not updated.');
        $this->assertIsObject($result, 'The result should be an object.');
        $this->assertInstanceOf(self::class, $result, 'The result has an incorrect instance type.');
        $this->assertSame($expectedStub, $stub, 'The variables were not replaced correctly within the stub.');
    }

    /**
     * A test for getting the vue post data string for non-edit types.
     *
     * @return void
     */
    public function test_get_vue_post_data_string_for_not_edit_types()
    {
        $input = $this->getMockColumns()[0];
        $expectedResult = "name-string: this.name-string,\n\t\t\t\t";

        $result = $this->getVuePostDataString($input);

        $this->assertSame($expectedResult, $result, 'The Vue post data string was incorrect.');
    }

    /**
     * A test for getting the vue post data string for edit types.
     *
     * @return void
     */
    public function test_get_vue_post_data_string_for_edit_type()
    {
        $type = 'edit';
        $input = $this->getMockColumns()[0];
        $expectedResult = "name-string: this.item.name-string,\n\t\t\t\t";

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($type) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getType')
                ->once()
                ->andReturn($type);
        });
        

        $result = $mock->getVuePostDataString($input);

        $this->assertSame($expectedResult, $result, 'The Vue post data string was incorrect.');
    }
}