<?php

namespace Cruddy;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;


class Blueprint extends BaseBlueprint
{
    /**
     * Specify a foreign key for the table.
     *
     * @param  string|array  $columns
     * @param  string|null  $name
     * @return \Illuminate\Database\Schema\ForeignKeyDefinition
     */
    public function foreign($columns, $name = null)
    {
        $command = new ForeignKeyDefinition(
            $this->indexCommand('foreign', $columns, $name)->getAttributes()
        );

        $this->commands[count($this->commands) - 1] = $command;

        return $command;
    }

}