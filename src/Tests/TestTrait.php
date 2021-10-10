<?php

namespace Cruddy\Tests;

use Illuminate\Database\Schema\ColumnDefinition;

trait TestTrait {

    /**
     * The name for the string column.
     *
     * @var string
     */
    protected $nameString = 'name-string';

    /**
     * The name for the integer column.
     *
     * @var string
     */
    protected $nameInteger = 'name-integer';

    /**
     * The name for the bigInteger column.
     *
     * @var string
     */
    protected $nameBigInteger = 'name-bigInteger';

    /**
     * Get an array of all the acceptable rules in an acceptable format.
     *
     * @return array
     */
    public function getMockColumns() : array
    {
        return [
            new ColumnDefinition([
                'type' => 'string',
                'name' => $this->nameString,
                'length' => 200,
                'inputType' => 'text',
            ]),
            new ColumnDefinition([
                'type' => 'integer',
                'name' => $this->nameInteger,
                'min' => 1,
                'max' => 1000,
                'inputType' => 'number',
            ]),
            // new ColumnDefinition([
            //     'type' => 'bigInteger',
            //     'name' => $this->nameBigInteger,
            //     'min' => 1,
            //     'max' => 9999,
            // ]),
        ];
    }
}