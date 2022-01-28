<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\ForeignKeys;

use Cruddy\Exceptions\UnknownRelationshipType;
use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\HasOneInput;
use Cruddy\Tests\TestTrait;
use Orchestra\Testbench\TestCase;

class ForeignKeyInputStubFactoryTest extends TestCase
{
    use TestTrait;

    /**
     * A test to get a valid foreign key input.
     *
     * @return void
     */
    public function test_get_valid_foreign_key_input()
    {
        $foreignKey = new ForeignKeyDefinition([
            'relationship' => 'hasOne'
        ]);
        
        $result = (new ForeignKeyInputStubFactory($foreignKey))
            ->get();

        $this->assertInstanceOf(HasOneInput::class, $result);
    }

    /**
     * A test to get a invalid foreign key input.
     *
     * @return void
     */
    public function test_get_invalid_foreign_key_input()
    {
        $this->expectException(UnknownRelationshipType::class);

        $foreignKey = new ForeignKeyDefinition([
            'relationship' => 'invalid-relationship-type'
        ]);
        
        (new ForeignKeyInputStubFactory($foreignKey))
            ->get();
    }
}