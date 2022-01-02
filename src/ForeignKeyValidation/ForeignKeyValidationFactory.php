<?php

namespace Cruddy\ForeignKeyValidation;

use Cruddy\ForeignKeyValidation\ModelRelationships\DefaultForeignKeyValidation;
use Cruddy\ForeignKeyDefinition;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;

class ForeignKeyValidationFactory
{
    /**
     * Get the correct ForeignKeyValidation for the relationship.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $rule
     * @return \Cruddy\ForeignKeyValidation\ForeignKeyValidation
     */
    public static function get(ForeignKeyDefinition $rule) : ForeignKeyValidation
    {
        $className = self::getClassName($rule);

        return class_exists($className) ? new $className($rule) : new DefaultForeignKeyValidation($rule);
    }

    /**
     * Get the validation rule class from the rule.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $rule
     * @return string
     */
    protected static function getClassName(ForeignKeyDefinition $rule) : string
    {
        return 'Cruddy\ForeignKeyValidation\ModelRelationships\\' . ucfirst($rule->inputType) . 'ForeignKeyValidation';
    }
}