<?php

namespace Cruddy\Tests\Unit\StubEditors\Inputs\Input\ForeignKeys;

use Cruddy\Exceptions\UnknownRelationshipType;
use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStubEditorFactory;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\HasOneInput;
use Cruddy\Tests\TestTrait;
use Orchestra\Testbench\TestCase;

class ForeignKeyInputStubEditorFactoryTest extends TestCase
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
        
        $result = (new ForeignKeyInputStubEditorFactory($foreignKey))
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
        
        (new ForeignKeyInputStubEditorFactory($foreignKey))
            ->get();
    }
}