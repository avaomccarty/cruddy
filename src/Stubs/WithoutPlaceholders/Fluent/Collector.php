<?php

namespace Cruddy\Stubs\WithoutPlaceholders\Rules;

use Cruddy\Stubs\CollectorTrait;

class Collector extends Base
{
    use CollectorTrait;

    /**
     * Determine if stub should be empty.
     *
     * @var boolean
     */
    protected $shouldHaveStub = false;

    /**
     * The collection stub class.
     *
     * @var string
     */
    protected $collectionStubClass = Collection::class;

    /**
     * Get the stub collection parameters. 
     *
     * @return array
     */
    protected function getParameters() : array
    {
        return [
            $this->column,
        ];
    }
}