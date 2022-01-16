<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\Columns\ViewColumnInputStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class ViewColumnInputStubEditorTest extends TestCase
{
    /**
     * A test to get the input string.
     *
     * @return void
     */
    public function test_get_input_string()
    {
        $nameOfResource = 'nameOfResource';
        $name = 'foo';
        $expectedResult = '<input type="submit" value="{{ $nameOfResource->foo }}" class="button is-primary my-2">' . "\n\n\t\t\t\t";
        $column = new ColumnDefinition([
            'type' => 'string',
            'name' => $name,
        ]);

        Config::shouldReceive('get')
            ->with('cruddy.stubs_folder')
            ->andReturn('stubs');

        Config::partialMock();

        $result = (new ViewColumnInputStubEditor($column))
            ->getInputString('index', $nameOfResource);

        $this->assertSame($expectedResult, $result);
    }
}