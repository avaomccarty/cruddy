<?php

namespace Cruddy\StubEditors;

use Illuminate\Database\Schema\ColumnDefinition;

class ForeignKeyInteractor
{
    /**
     * Get the column foreign keys.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  array  $foreignKeys
     * @return array
     */
    public static function getColumnForeignKeys(ColumnDefinition $column, array $foreignKeys) : array
    {
        return array_filter($foreignKeys, function ($foreignKey) use ($column) {
            return in_array($foreignKey->columns, $column->name);
        });
    }
}