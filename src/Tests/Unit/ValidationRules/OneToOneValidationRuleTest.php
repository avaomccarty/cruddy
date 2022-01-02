<?php

namespace Cruddy\Tests\Unit\ForeignKeyValidation;

use Cruddy\Tests\TestTrait;
use Cruddy\ForeignKeyValidation\ModelRelationships\OneToOneForeignKeyValidation;
use Orchestra\Testbench\TestCase;

class OneToOneForeignKeyValidationTest extends TestCase
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

        $result = (new OneToOneForeignKeyValidation($foreignKey))
            ->getForeignKeyValidation();

        $this->assertSame('exists:users,id', $result);
    }
}