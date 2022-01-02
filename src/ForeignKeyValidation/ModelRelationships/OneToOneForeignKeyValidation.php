<?php

namespace Cruddy\ForeignKeyValidation\ModelRelationships;

use Cruddy\ForeignKeyValidation\ForeignKeyValidation;

class OneToOneForeignKeyValidation extends ForeignKeyValidation
{
    /**
     * Get the validation rule for a one-to-one relationship.
     *
     * @return string
     */
    public function getForeignKeyValidation() : string
    {
        return 'exists:' . $this->foreignKey->on . ',' . $this->foreignKey->references;
    }
}