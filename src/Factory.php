<?php

namespace Cruddy;

use Illuminate\Support\Facades\App;

abstract class Factory
{
    /**
     * The parameters for the new class.
     *
     * @var array
     */
    private $parameters = [];

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setParameters();
    }

    /**
     * Set the parameters.
     *
     * @return void
     */
    abstract protected function setParameters() : void;

    /**
     * Make an instance of the provided class.
     *
     * @param  string  $class
     * @return mixed
     */
    public function makeClass(string $class) : mixed
    {
        return App::make($class, $this->parameters);
    }

    /**
     * Get the reource using the factory.
     *
     * @return mixed
     */
    abstract public function get() : mixed;
}