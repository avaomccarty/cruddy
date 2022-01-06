<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\ForeignKeys;

use Cruddy\ForeignKeyDefinition;
use Cruddy\Tests\TestTrait;
use Orchestra\Testbench\TestCase;

class HasOneInputTest extends TestCase
{
    use TestTrait;

    /**
     * A test to get the input string.
     *
     * @return void
     */
    public function test_get_input_string()
    {
        $foreignKey = new ForeignKeyDefinition([
            'relationship' => 'hasOne'
        ]);
    }
}