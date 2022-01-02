<?php

namespace Cruddy\Tests\Feature;

use Cruddy\Exceptions\UnknownRelationshipType;
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
            'inputType' => 'hasOne',
            'classes' => $this->classes,
            'keys' => $this->keys,
            'on' => 'users',
        ]);
    }

    // /**
    //  * A test for an invalid relationship type.
    //  *
    //  * @return void
    //  */
    // public function test_invalid_relationship_type()
    // {
    //     // $this->expectException(UnknownRelationshipType::class);

    //     $foreignKey = new ForeignKeyDefinition([
    //         'inputType' => 'invalid-type',
    //         'on' => 'on'
    //     ]);

    //     // new ModelRelationship($foreignKey);

    //     $this->artisan('cruddy:model', [
    //         'name' => 'name',
    //         'inputs' => $this->getMockColumns(),
    //         'keys' => [$foreignKey],
    //     ]);
    // }

    /**
     * A test to get the default relationship stub.
     *
     * @return void
     */
    public function test_default_relationship_stub()
    {
        $expectedResult = "\n\t\t/**\n\t\t * Get the associated roles.\n\t\t */\n\t\tpublic function roles()\n\t\t{\n\t\t    return \$this->hasOne();\n\t\t}\n\t\t";
        $foreignKey = new ForeignKeyDefinition([
            'inputType' => 'hasOne',
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
        $expectedResult = "\n\t\t/**\n\t\t * Get the associated users.\n\t\t */\n\t\tpublic function users()\n\t\t{\n\t\t    return \$this->hasMany(Foo::class, Bar\Baz::class);\n\t\t}\n\t\t";
        $foreignKey = new ForeignKeyDefinition([
            'inputType' => 'hasMany',
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
        $expectedResult = "\n\t\t/**\n\t\t * Get the associated users.\n\t\t */\n\t\tpublic function users()\n\t\t{\n\t\t    return \$this->hasOne(Foo::class, Bar\Baz::class, 'key-1', 'key-2');\n\t\t}\n\t\t";

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