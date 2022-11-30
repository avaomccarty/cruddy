<?php

namespace Cruddy;

use Illuminate\Database\Schema\ForeignKeyDefinition as BaseForeignKeyDefinition;

class ForeignKeyDefinition extends BaseForeignKeyDefinition
{
    /**
     * The relationship for the foreign key.
     *
     * @var string
     */
    protected $cruddyRelationship = '';

    /**
     * Add a relationship to the foreign key.
     *
     * @param  string  $relationship
     * @return void
     */
    protected function relationship(string $relationship) : void
    {
        $this->cruddyRelationship = $relationship;
    }
}