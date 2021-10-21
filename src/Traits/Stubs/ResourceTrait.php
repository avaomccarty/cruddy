<?php

namespace Cruddy\Traits\Stubs;

trait ResourceTrait
{
    use StubTrait;

    /**
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
    protected $resourcePlaceholders = [
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
        $this->replaceInStub($this->resourcePlaceholders, $this->getResource(), $stub);

        return $this;
    }

}