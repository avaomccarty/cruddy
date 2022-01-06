<?php

namespace Cruddy;

use Illuminate\Support\Fluent;

class FluentInteractor
{
    /**
     * Determine if the column is for a ColumnDefinition.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return boolean
     */
    public static function isAColumnDefinition(Fluent $column) : bool
    {
        // Todo: add unit tests
        return is_a($column, ColumnDefinition::class);
    }

    /**
     * Determine if the column is for a ForeignKey.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return boolean
     */
    public static function isAForeignKey(Fluent $column) : bool
    {
        // Todo: add unit tests
        return is_a($column, ForeignKeyDefinition::class);
    }
}