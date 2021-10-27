<?php

namespace Cruddy\Traits;

trait ControllerMakeCommandTrait
{
    use CommandTrait;

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = "\n\t\t\t\t";

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
        $stub = $this->getStubFile();
        $inputsString = $this->getControllerInputsString();

        if ($this->getModel()) {
            $this->replaceModel($stub, $name);
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceInStub($this->resourcePlaceholders, $this->getResource(), $stub)
            ->replaceInStub($this->inputPlaceholders, $inputsString, $stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the name for the resource.
     *
     * @return string
     */
    protected function getResource() : string
    {
        return str_ireplace('controller', '', $this->getNameString());
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModel(string &$stub) : self
    {
        $modelClass = $this->parseModel($this->getModel());

        if (! class_exists($modelClass)) {
            $this->call('cruddy:model', [
                'name' => $modelClass,
                '--inputs' => $this->getInputs(),
            ]);
        }

        $modelClassName = $this->getClassBasename($modelClass);
        
        return $this->replaceInStub($this->modelPlaceholders, $modelClassName, $stub)
            ->replaceInStub($this->modelVariablePlaceholders, $modelClassName, $stub)
            ->replaceInStub($this->fullModelClassPlaceholders, $modelClass, $stub);
    }
}