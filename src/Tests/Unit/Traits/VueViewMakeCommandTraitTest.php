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
}