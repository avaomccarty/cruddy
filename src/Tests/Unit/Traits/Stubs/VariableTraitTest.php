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
     * A test to replace the variable collection placeholder.
     *
     * @return void
     */
    public function test_replace_variable_collection_placeholder()
    {
        $originalStub = $expectedStub = $stub = 'stub-';
        $variable = 'variable';
        $camelCasePluralVariable = 'camelCasePluralVariable';

        foreach ($this->variableCollectionPlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $camelCasePluralVariable;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($variable, $camelCasePluralVariable) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getCamelCasePlural')
                ->with($variable)
                ->once()
                ->andReturn($camelCasePluralVariable);
        });
        

        $result = $mock->replaceVariableCollectionPlaceholders($variable, $stub);

        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not replace the model variables correctly.');
    }

    /**
     * A test to replace the variable placeholders.
     *
     * @return void
     */
    public function test_replace_variable_placeholders()
    {
        $originalStub = $expectedStub = $stub = 'stub-';
        $variable = 'variable';
        $camelCaseSingularVariable = 'camelCaseSingularVariable';

        foreach ($this->variablePlaceholders as $placeholder) {
            $stub .= $placeholder;
            $expectedStub .= $camelCaseSingularVariable;
        }

        $mock = $this->partialMock(self::class, function (MockInterface $mock) use ($variable, $camelCaseSingularVariable) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getCamelCaseSingular')
                ->with($variable)
                ->once()
                ->andReturn($camelCaseSingularVariable);
        });

        $result = $mock->replaceVariablePlaceholders($variable, $stub);

        $this->assertIsObject($result);
        $this->assertInstanceOf(self::class, $result);
        $this->assertNotSame($originalStub, $stub, 'The stub should have been updated.');
        $this->assertSame($expectedStub, $stub, 'The stub did not replace the model variables correctly.');
    }
}