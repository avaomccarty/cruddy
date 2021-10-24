<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\RuleTrait;

trait RequestMakeCommandTrait
{
    use CommandTrait, RuleTrait;

    /**
     * The acceptable types of requests.
     *
     * @var array
     */
    protected $types = [
        'update',
        'store'
    ];

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\Http\Requests';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput() : string
    {
        $type = $this->getType();
        $name = $this->getResourceName();

        return $this->getStudlySingular($type) . $this->getStudlySingular($name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->resolveStubPath($this->getStubsLocation() . '/request.stub');
    }
}
