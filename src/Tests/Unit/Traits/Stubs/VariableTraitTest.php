<?php

namespace Cruddy\Tests\Unit\Traits\Stubs;

use Cruddy\Tests\TestTrait;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\VariableTrait;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class VariableTraitTest extends TestCase
{
    use CommandTrait, VariableTrait, TestTrait;

    /**
     * A test for getting the camel case singular version of the value.
     *
     * @return void
     */
    public function test_get_camel_case_singular()
    {
        $value = 'TestVariableNames';
        $expectedResult = 'testVariableName';

        $result = $this->getCamelCaseSingular($value);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test for getting the camel case plural version of the value.
     *
     * @return void
     */
    public function test_get_camel_case_plural()
    {
        $value = 'TestVariableName';
        $expectedResult = 'testVariableNames';

        $result = $this->getCamelCasePlural($value);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the edit URL for any non-Vue frontend.
     *
     * @return void
     */
    public function test_get_edit_url_for_non_vue_frontend()
    {
        $name = 'name';
        $camelCaseSingular = 'camelCaseSingular';
        $expectedResult = '/' . $name . '/{{ $' . $camelCaseSingular . '->id }}/edit';

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($name, $camelCaseSingular) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(false);
            $mock->shouldReceive('getCamelCaseSingular')
                ->with($name)
                ->once()
                ->andReturn($camelCaseSingular);
        });

        $result = $mock->getEditUrl($name);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the edit URL for any Vue frontend.
     *
     * @return void
     */
    public function test_get_edit_url_for_vue_frontend()
    {
        $name = 'name';
        $expectedResult = "'/$name/' + item.id + '/edit'";

        $mock = $this->partialMock(self::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('needsVueFrontend')
                ->once()
                ->andReturn(true);
            $mock->shouldReceive('getCamelCaseSingular')
                ->never();
        });

        $result = $mock->getEditUrl($name);

        $this->assertSame($expectedResult, $result);
    }
}