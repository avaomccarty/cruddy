<?php

namespace Cruddy\Traits\Stubs;

trait ResourceTrait
{
    /**
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
    protected $stubResourcePlaceholders = [
        'DummyResource',
        '{{ resource }}',
        '{{resource}}'
    ];

    /**
     * Replace the resource for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceResource(string &$stub) : self
    {
        $stub = str_replace($this->stubResourcePlaceholders, $this->getResource(), $stub);

        return $this;
    }

}