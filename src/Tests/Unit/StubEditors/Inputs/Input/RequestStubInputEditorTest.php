<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input;

use Cruddy\ForeignKeyDefinition;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\ForeignKeyValidation\ModelRelationships\OneToOneForeignKeyValidation;
use Cruddy\StubEditors\Inputs\Input\RequestStubInputEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Orchestra\Testbench\TestCase;

class RequestStubInputEditorTest extends TestCase
{
    /**
     * A test to get the input string.
     *
     * @return void
     */
    public function test_get_input_string()
    {
        $expectedResult = "'name' => 'exists:on,references',\n\t\t\t";
        $name = 'name';
        $expectedForeignKey = new ForeignKeyDefinition([
            'inputType' => 'oneToOne',
            'columns' => [$name],
            'on' => 'on',
            'references' => 'references',
        ]);
        $otherForeignKey = new ForeignKeyDefinition([
            'inputType' => 'oneToOne',
            'columns' => ['other-name'],
            'on' => 'other-on',
            'references' => 'other-references',
        ]);
        $column = new ColumnDefinition([
            'type' => 'string',
            'name' => $name,
        ]);

        $foreignKeys = [
            $expectedForeignKey,
            $otherForeignKey,
        ];

        $foreignKeyValidation = new OneToOneForeignKeyValidation($expectedForeignKey);
        App::shouldReceive('make')
            ->with(ForeignKeyValidation::class, [$expectedForeignKey])
            ->once()
            ->andReturn($foreignKeyValidation);
        App::shouldReceive('make')
            ->with(ForeignKeyValidation::class, [$otherForeignKey])
            ->never();

        $result = (new RequestStubInputEditor($column))
            ->setForeignKeys($foreignKeys)
            ->getInputString();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the input string without setting foreign keys.
     *
     * @return void
     */
    public function test_get_input_string_without_setting_foreign_keys()
    {
        $expectedResult = "'name' => '',\n\t\t\t";
        $name = 'name';
        $column = new ColumnDefinition([
            'type' => 'string',
            'name' => $name,
        ]);

        App::shouldReceive('make')
            ->never();

        $result = (new RequestStubInputEditor($column))
            ->getInputString();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to set the foreign keys.
     *
     * @return void
     */
    public function test_set_foreign_keys()
    {
        $column = new ColumnDefinition([]);

        $result = (new RequestStubInputEditor($column))
            ->setForeignKeys([]);

        $this->assertInstanceOf(RequestStubInputEditor::class, $result);
    }
}