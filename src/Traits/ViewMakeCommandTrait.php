<?php

namespace Cruddy\Traits;
trait ViewMakeCommandTrait
{
    use ConfigTrait;

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\resources\views';
    }
}