<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns\Input;

use Cruddy\StubEditors\EmptyPlaceholderStubParametersTrait;

trait AttributeTrait
{
    use EmptyPlaceholderStubParametersTrait;

    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = ' ';
}