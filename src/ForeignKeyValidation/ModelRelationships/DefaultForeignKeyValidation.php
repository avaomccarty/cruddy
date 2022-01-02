<?php

namespace Cruddy\ForeignKeyValidation\ModelRelationships;

use Cruddy\ForeignKeyValidation\ForeignKeyValidation;

class DefaultForeignKeyValidation extends ForeignKeyValidation
{
    /**
     * Get the validation for the default relationship.
     *
     * @return string
     */
    public function getForeignKeyValidation() : string
    {
        return '';
    }

    /**
     * Add the start of the validation rule.
     *
     * @return void
     */
    public function addValidationStart() : void
    {
        return;
    }

    /**
     * Add the column to the validation rule.
     *
     * @return void
     */
    public function addColumnToValidation() : void
    {
        return;
    }
}