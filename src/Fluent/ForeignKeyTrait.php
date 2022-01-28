<?php

namespace Cruddy\Fluent;

use Cruddy\ForeignKeyDefinition;
use Illuminate\Support\Fluent;

trait ForeignKeyTrait
{
    /**
     * Determine if the column is for a specific Fluent type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return boolean
     */
    public static function isAForeignKeyDefinition(Fluent $column) : bool
    {
        return is_a($column, ForeignKeyDefinition::class);
    }

    /**
     * Get the foreign keys.
     *
     * @param  \Illuminate\Support\Fluent[]  $columns
     * @return array
     */
    public static function getForeignKeys(array $columns) : array
    {
        // Todo: add unit tests
        return array_filter($columns, function ($column) {
            return self::isAForeignKeyDefinition($column);
        });
    }
}