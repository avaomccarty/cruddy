<?php

namespace Cruddy\Fluent;

use Illuminate\Support\Fluent;

trait FluentTrait
{
    /**
     * Get the column class.
     *
     * @return string
     */
    abstract protected static function getColumnClass() : string;

    /**
     * Determine if the column is for a specific Fluent type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return boolean
     */
    protected static function isA(Fluent $column) : bool
    {
        return is_a($column, self::getColumnClass());
    }

    /**
     * Filter the columns by type.
     *
     * @param  array  $columns
     * @return array
     */
    protected static function filterColumns(array $columns) : array
    {
        return array_filter($columns, function ($column) {
            return self::isA($column);
        });
    }
}