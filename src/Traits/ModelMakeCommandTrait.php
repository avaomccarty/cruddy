<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\InputTrait;
use Cruddy\Traits\Stubs\StubTrait;

trait ModelMakeCommandTrait
{
    use CommandTrait, InputTrait, StubTrait;

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->resolveStubPath($this->getStubsLocation() . '/model.stub');
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

        return $this->replaceNamespace($stub, $name)
            ->replaceModelInputs($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModelInputs(string &$stub) : self
    {
        $inputs = $this->getInputs();
        $inputsString = '';

        foreach ($inputs as $input) {
            $inputsString .= $this->getInputString($input);
        }

        $this->removeEndOfLineFormatting($inputsString);
        $this->replaceModelPlaceholders($inputsString, $stub);

        return $this;
    }
}