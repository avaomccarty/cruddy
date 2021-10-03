<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Schema\ColumnDefinition;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;

class ModelMakeCommand extends BaseModelMakeCommand
{
    use CommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:model';

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/model.stub');
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

        $hasInput = false;
        $inputsString = '';

        foreach ($inputs as $input) {
            $hasInput = true;
            $inputsString .= $this->getInputString($input);
        }

        if ($hasInput) {
            // Remove extra formatting at the end of string
            $inputsString = substr($inputsString, 0, strlen($inputsString) - 3);
        }

        $stub = str_replace($this->stubVariableModels, $inputsString, $stub);

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
}
