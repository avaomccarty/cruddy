<?php

namespace Cruddy\Tests\Unit\ForeignKeyValidation;

use Cruddy\Tests\TestTrait;
use Cruddy\ForeignKeyValidation\ForeignKeyValidationStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Cruddy\ForeignKeyDefinition;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class ValidationStubEditorTest extends TestCase
{
    use TestTrait;

    public function setUp() : void
    {
        parent::setUp();
        $this->stub = 'stub';
    }

    /**
     * A test to determine if a Fluent rule is a column.
     *
     * @return void
     */
    public function test_is_a_column()
    {
        $column = $this->getMockColumns()[0];
        $foreignKey = $this->getMockCommands()[0];

        $mock = $this->partialMock(ForeignKeyValidationStubEditor::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
        });

        $this->assertTrue($mock->isAColumn($column));
        $this->assertFalse($mock->isAColumn($foreignKey));
    }

    /**
     * A test to determine if a Fluent rule is a foreign key.
     *
     * @return void
     */
    public function test_is_a_foreign_key()
    {
        $column = $this->getMockColumns()[0];
        $foreignKey = $this->getMockCommands()[0];

        $mock = $this->partialMock(ForeignKeyValidationStubEditor::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
        });

        $this->assertFalse($mock->isAForeignKey($column));
        $this->assertTrue($mock->isAForeignKey($foreignKey));
    }

    /**
     * A test to update a stub with the rules.
     *
     * @return void
     */
    public function test_update_stub_with_rules()
    {
        $columns = $this->getMockColumns();
        $mock = $this->partialMock(ForeignKeyValidationStubEditor::class, function (MockInterface $mock) use ($columns) {
            $mock->shouldAllowMockingProtectedMethods();
            foreach ($columns as $column) {
                $mock->shouldReceive('addDefaultForeignKeyValidations')
                    ->with($column->type)
                    ->once();
                $mock->shouldReceive('addColumnForeignKeyValidations')
                    ->with($column)
                    ->once();
                $mock->shouldReceive('addForeignKeyRules')
                    ->with($column)
                    ->once();
            }

            $mock->shouldReceive('removeEndOfLineFormatting')
                ->once();
            $mock->shouldReceive('replaceInStub')
                ->once();
        });
        
        foreach ($columns as $column) {
            $mock->addColumn($column);
        }

        $mock->updateStubWithRules();
    }

    /**
     * A test to determine if a foreign key is for a column.
     *
     * @return void
     */
    public function test_is_foreign_key_for_column()
    {
        $column = 'column';
        $foreignKeyOther = new ForeignKeyDefinition([
            'columns' => [
                'other'
            ]
        ]);
        $foreignKeyForColumn = new ForeignKeyDefinition([
            'columns' => [
                $column
            ]
        ]);
        $mock = $this->partialMock(ForeignKeyValidationStubEditor::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
        });

        $this->assertTrue($mock->isForeignKeyForColumn($foreignKeyForColumn, $column));
        $this->assertFalse($mock->isForeignKeyForColumn($foreignKeyOther, $column));
    }

    /**
     * A test to add the foreign key rules.
     *
     * @return void
     */
    public function test_add_foreign_key_frules()
    {
        $name = 'name';
        $column = new ColumnDefinition([
            'name' => $name
        ]);
        $foreignKeys = [
            new ForeignKeyDefinition([
                'name' => 'other-1'
            ]),
            new ForeignKeyDefinition([
                'name' => 'other-2'
            ]),
        ];
        $mock = $this->partialMock(ForeignKeyValidationStubEditor::class, function (MockInterface $mock) use ($column, $foreignKeys) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('getForeignKeysForColumn')
                ->with($column)
                ->once()
                ->andReturn($foreignKeys);
            
                foreach ($foreignKeys as $foreignKey) {
                    $mock->shouldReceive('addCommandForeignKeyValidations')
                        ->with($foreignKey)
                        ->once();
                }
        });

        $mock->addForeignKeyRules($column);
    }

    /**
     * A test to get the foreign keys for a column.
     *
     * @return void
     */
    public function test_get_foreign_keys_for_column()
    {
        $name = 'name';
        $id = 3;
        $column = new ColumnDefinition([
            'name' => $name
        ]);
        $foreignKeys = [
            new ForeignKeyDefinition([
                'id' => 1,
                'return' => false
            ]),
            new ForeignKeyDefinition([
                'id' => 2,
                'return' => false
            ]),
            new ForeignKeyDefinition([
                'id' => $id,
                'return' => true
            ]),
            new ForeignKeyDefinition([
                'id' => 4,
                'return' => false
            ]),
        ];

        $mock = $this->partialMock(ForeignKeyValidationStubEditor::class, function (MockInterface $mock) use ($name, $foreignKeys) {
            $mock->shouldAllowMockingProtectedMethods();
            foreach ($foreignKeys as $foreignKey) {
                $mock->shouldReceive('isForeignKeyForColumn')
                    ->with($foreignKey, $name)
                    ->once()
                    ->andReturn($foreignKey->return);
            }
        });

        foreach ($foreignKeys as $foreignKey) {
            $mock->addForeignKey($foreignKey);
        }

        $result = $mock->getForeignKeysForColumn($column);

        $this->assertTrue(count($result) === 1);
        $this->assertSame($id, last($result)->id);
    }
}