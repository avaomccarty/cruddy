<?php

namespace Cruddy\ForeignKeyValidation;

use Cruddy\ForeignKeyDefinition;

class ForeignKeyValidation
{
    /**
     * The foreign key.
     *
     * @var \Cruddy\ForeignKeyDefinition
     */
    protected $foreignKey;

    /**
     * The constructor method.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return void
     */
    public function __construct(ForeignKeyDefinition $foreignKey)
    {
        $this->foreignKey = $foreignKey;
    }

    /**
     * Get the validation for the default relationship.
     *
     * @return string
     */
    public function getForeignKeyValidation() : string
    {
        return 'exists:' . $this->foreignKey->on . ',' . $this->foreignKey->references;
    }
}