<?php

namespace Cruddy\Traits;

use Illuminate\Database\Schema\ColumnDefinition;
use Symfony\Component\Console\Input\InputOption;

trait ModelMakeCommandTrait
{
    use CommandTrait;

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
            ->replaceInputs($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceInputs(string &$stub) : self
    {
        $inputs = $this->option('inputs');
        $inputsString = '';

        foreach ($inputs as $input) {
            $inputsString .= $this->getInputString($input);
        }

        if (count($inputs) > 0) {
            // Remove extra formatting at the end of string
            $inputsString = substr($inputsString, 0, strlen($inputsString) - 3);
        }

        $stub = str_replace($this->stubModelPlaceholders, $inputsString, $stub);

        return $this;
    }

    /**
     * Get the input needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getInputString(ColumnDefinition $input) : string
    {
        if ($input['name'] === 'id') {
            return '';
        }

        return "'" . $input['name'] . "',\n\t\t";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() : array
    {
        $options = parent::getOptions();

        $options[] = ['inputs', null, InputOption::VALUE_OPTIONAL, 'The inputs for the resource'];

        return $options;
    }
}