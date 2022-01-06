<?php

namespace Cruddy\Tests\Unit\ForeignKeyValidation;

use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\Tests\TestTrait;
use Orchestra\Testbench\TestCase;

class ForeignKeyValidationTest extends TestCase
{
    use TestTrait;

    /**
     * A test to get the validation rule.
     *
     * @return void
     */
    public function test_get_validation_rule()
    {
        $foreignKey = $this->getMockCommands()[0];

        $result = (new ForeignKeyValidation($foreignKey))
            ->getForeignKeyValidation();

        $this->assertSame('exists:users,id', $result);
    }
}