<?php

namespace Cruddy\Commands;

use Cruddy\Traits\ModelMakeCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;

class ModelMakeCommand extends BaseModelMakeCommand
{
    use ModelMakeCommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:model';


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
        $stub = $this->getStubFile();
        $inputsString = $this->getInputsString($this->typeNeedsSubmitInput());

        return $this->replaceNamespace($stub, $name)
            ->replaceInStub($this->inputPlaceholders, $inputsString, $stub)
            ->replaceClass($stub, $name);
    }
}
