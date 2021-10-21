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
}