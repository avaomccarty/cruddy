<?php

namespace Cruddy\Fluent;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Fluent;

trait ColumnDefinitionTrait
{
    /**
     * Determine if the column is for a specific Fluent type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return boolean
     */
    public static function isAColumnDefinition(Fluent $column) : bool
    {
        return is_a($column, ColumnDefinition::class);
    }

    /**
     * Get the foreign keys.
     *
     * @param  \Illuminate\Support\Fluent[]  $columns
     * @return array
     */
    public static function getColumnDefinitions(array $columns) : array
    {
        // Todo: add unit tests
        return array_filter($columns, function ($column) {
            return self::isAColumnDefinition($column);
        });
    }
}