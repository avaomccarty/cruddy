<?php

namespace Cruddy\Tests\Feature;

use Cruddy\ModelRelationships\ModelRelationship;
use Cruddy\Tests\TestTrait;
use Orchestra\Testbench\TestCase;
use Cruddy\ForeignKeyDefinition;

class ModelRelationshipTest extends TestCase
{
    use TestTrait;

    /**
     * The foreign key.
     *
     * @var \Cruddy\ForeignKeyDefinition
     */
    public $foreignKey;

    public function setUp() : void
    {
        parent::setUp();
        $this->classes = [
            'Foo',
            'Bar\Baz',
        ];
        $this->keys = [
            'key-1',
            'key-2',
        ];
        $this->foreignKey = new ForeignKeyDefinition([
            'relationship' => 'hasOne',
            'classes' => $this->classes,
            'keys' => $this->keys,
            'on' => 'users',
        ]);
    }

    /**
     * A test to get the default relationship stub.
     *
     * @return void
     */
    public function test_default_relationship_stub()
    {
        $expectedResult = "\n\t/**\n\t * Get the associated roles.\n\t */\n\tpublic function roles()\n\t{\n\t    return \$this->hasOne();\n\t}\n\t";
        $foreignKey = new ForeignKeyDefinition([
            'relationship' => 'hasOne',
            'on' => 'roles',
        ]);

        $result = (new ModelRelationship($foreignKey))
            ->getModelRelationshipStub();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the default relationship stub.
     *
     * @return void
     */
    public function test_relationship_stub_with_classes_and_without_keys()
    {
        $expectedResult = "\n\t/**\n\t * Get the associated users.\n\t */\n\tpublic function users()\n\t{\n\t    return \$this->hasMany(Foo::class, Bar\Baz::class);\n\t}\n\t";
        $foreignKey = new ForeignKeyDefinition([
            'relationship' => 'hasMany',
            'on' => 'users',
            'classes' => $this->classes
        ]);

        $result = (new ModelRelationship($foreignKey))
            ->getModelRelationshipStub();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the default relationship stub.
     *
     * @return void
     */
    public function test_relationship_stub_with_classes_and_with_keys()
    {
        $expectedResult = "\n\t/**\n\t * Get the associated users.\n\t */\n\tpublic function users()\n\t{\n\t    return \$this->hasOne(Foo::class, Bar\Baz::class, 'key-1', 'key-2');\n\t}\n\t";

        $result = (new ModelRelationship($this->foreignKey))
            ->getModelRelationshipStub();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the default relationship stub.
     *
     * @return void
     */
    public function test_get_model_use_statement()
    {
        $expectedResult = "use App\Models\User;\n";

        $result = (new ModelRelationship($this->foreignKey))
            ->getUseStatement();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * A test to get the acceptable relationship model relationships.
     *
     * @return void
     */
    public function test_get_model_relationship_types()
    {
        $expectedResult = [
            'default',
            'hasOne',
            'hasMany',
            'belongsTo',
            'hasOneThrough',
            'hasManyThrough',
            'belongsToMany',
            'morphTo',
            'morphOne',
            'morphMany',
            'morphToMany',
            'morphedByMany',
        ];

        $result = ModelRelationship::getValidRelationshipTypes();

        $this->assertSame($expectedResult, $result);
    }
}