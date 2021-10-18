<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\ResourceTrait;

trait ControllerMakeCommandTrait
{
    use CommandTrait, ResourceTrait;

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name) : string
    {
        $stub = $this->files->get($this->getStub());

        if ($this->option('model')) {
            $this->replaceModel($stub, $name);
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceResource($stub)
            ->replaceInputs($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the name for the resource.
     *
     * @return string
     */
    protected function getResource() : string
    {
        return str_ireplace('controller', '', $this->argument('name'));
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModel(string &$stub) : self
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (! class_exists($modelClass)) {
            $this->call('cruddy:model', [
                'name' => $modelClass,
                '--inputs' => $this->option('inputs'),
            ]);
        }

        $this->replaceModelPlaceholders($modelClass, $stub)
            ->replaceModelVariablePlaceholders($modelClass, $stub)
            ->replaceFullModelPlaceholders($modelClass, $stub);

        return $this;
    }
}