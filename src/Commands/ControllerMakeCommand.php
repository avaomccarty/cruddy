<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\InputTrait;
use Cruddy\Traits\Stubs\ResourceTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use CommandTrait, InputTrait, ResourceTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() : string
    {
        if ($this->option('api')) {
            return $this->resolveStubPath($this->getStubsLocation() . '/controller.api.stub');
        }

        return $this->resolveStubPath($this->getStubsLocation() . '/controller.stub');
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
            $inputsString = substr($inputsString, 0, strlen($inputsString) - 5);
        }

        $stub = str_replace($this->stubInputPlaceholders, $inputsString, $stub);

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

        return "'" . $input['name'] . "'" . ' => $request->' . $input['name'] . ",\n\t\t\t\t";
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
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('cruddy:model', [
                    'name' => $modelClass,
                    '--inputs' => $this->option('inputs'),
                ]);
            }
        }

        // $stub = str_replace($this->stubModelPlaceholders, class_basename($modelClass), $stub);
        // $stub = str_replace($this->stubModelVariablePlaceholders, lcfirst(class_basename($modelClass)), $stub);
        // $stub = str_replace($this->stubFullModelClassPlaceholders, $modelClass, $stub);
        
        $this->replaceModelPlaceholder($modelClass, $stub)
            ->replaceModelVariablePlaceholders($modelClass, $stub)
            ->replaceFullModelPlaceholders($modelClass, $stub);

        return $this;
    }

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
