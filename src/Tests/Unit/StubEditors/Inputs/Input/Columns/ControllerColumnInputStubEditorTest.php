<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\Columns\ControllerColumnInputStub;
use Illuminate\Database\Schema\ColumnDefinition;
use Orchestra\Testbench\TestCase;

class ControllerColumnInputStubTest extends TestCase
{
    /**
     * A test to get the input string.
     *
     * @return void
     */
    public function test_get_input_string()
    {
        $name = 'foo';
        $expectedResult = "'foo' => \$request->foo,\n\t\t\t\t";
        $column = new ColumnDefinition([
            'type' => 'string',
            'name' => $name,
        ]);

        $result = (new ControllerColumnInputStub($column))
            ->getInputString();

        $this->assertSame($expectedResult, $result);
    }
}