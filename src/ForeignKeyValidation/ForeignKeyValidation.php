<?php

namespace Cruddy\ForeignKeyValidation;

use Cruddy\ForeignKeyDefinition;

abstract class ForeignKeyValidation
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
     * Get the validation rule.
     *
     * @return string
     */
    abstract public function getForeignKeyValidation() : string;
}