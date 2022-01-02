<?php

namespace Cruddy\Tests\Unit\ForeignKeyValidation;

use Cruddy\Tests\TestTrait;
use Cruddy\ForeignKeyValidation\ModelRelationships\OneToOneForeignKeyValidation;
use Cruddy\ForeignKeyValidation\ModelRelationships\DefaultForeignKeyValidation;
use Cruddy\ForeignKeyValidation\ForeignKeyValidationFactory;
use Cruddy\ForeignKeyDefinition;
use Orchestra\Testbench\TestCase;

class ForeignKeyValidationFactoryTest extends TestCase
{
    use TestTrait;

    /**
     * A test for unknown validation rule class default.
     *
     * @return void
     */
    public function test_get_validation_rule_default()
    {
        $rule = new ForeignKeyDefinition([
            'inputType' => '',
        ]);
        
        $result = ForeignKeyValidationFactory::get($rule);

        $this->assertInstanceOf(DefaultForeignKeyValidation::class, $result);
    }

    /**
     * A test to get the validation rule class for a one-to-one relationship.
     *
     * @return void
     */
    public function test_get_validation_rule_class_for_one_to_one_relationship()
    {
        $rule = new ForeignKeyDefinition([
            'inputType' => 'oneToOne',
        ]);

        $result = ForeignKeyValidationFactory::get($rule);

        $this->assertInstanceOf(OneToOneForeignKeyValidation::class, $result);
    }
}