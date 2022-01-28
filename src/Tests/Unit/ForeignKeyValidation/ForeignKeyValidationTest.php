<?php

namespace Cruddy\Tests\Unit\ForeignKeyValidationStub;

use Cruddy\StubEditors\Validation\ForeignKeyValidationStub;
use Cruddy\Tests\TestTrait;
use Orchestra\Testbench\TestCase;

class ForeignKeyValidationStubTest extends TestCase
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

        $result = (new ForeignKeyValidationStub($foreignKey))
            ->getForeignKeyValidationStub();

        $this->assertSame('exists:users,id', $result);
    }
}