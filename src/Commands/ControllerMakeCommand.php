<?php

namespace Cruddy\Commands;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
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
    protected function getStub()
    {
        if ($this->option('api')) {
            return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/controller.api.stub');
        }

        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/controller.stub');
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        if ($this->option('model')) {
            $this->replaceModel($stub, $name);
        }

        return $this->replaceNamespace($stub, $name)->replaceResource($stub)->replaceInputs($stub)->replaceClass($stub, $name);
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceInputs(&$stub)
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

        $stub = str_replace(['DummyInputs', '{{ inputs }}', '{{inputs}}'], $inputsString, $stub);

        return $this;
    }

    /**
     * Get the input needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    public function getInputString(ColumnDefinition $input)
    {
        if ($input['name'] === 'id') {
            return;
        }

        return "'" . $input['name'] . "'" . ' => $request->' . $input['name'] . ",\n\t\t\t\t";
    }

    /**
     * Get the name for the resource.
     *
     * @return string
     */
    public function getResource()
    {
        return str_ireplace('controller', '', $this->argument('name'));
    }


    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (! class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('cruddy:model --factory', [
                    'name' => $modelClass,
                    '--inputs' => $this->option('inputs'),
                ]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceModel(&$stub)
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

        $stub = str_replace(['DummyModelClass', '{{ model }}', '{{model}}'], class_basename($modelClass), $stub);
        $stub = str_replace(['DummyModelVariable', '{{ modelVariable }}', '{{modelVariable}}'], lcfirst(class_basename($modelClass)), $stub);
        $stub = str_replace(['DummyFullModelClass', '{{ namespacedModel }}', '{{namespacedModel}}'], $modelClass, $stub);

        return $this;
    }

    /**
     * Replace the resource for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceResource(&$stub)
    {
        $stub = str_replace(['DummyResource', '{{ resource }}', '{{resource}}'], $this->getResource(), $stub);

        return $this;
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['inputs', null, InputOption::VALUE_OPTIONAL, 'The inputs for the resource'];

        return $options;
    }
}
