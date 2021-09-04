<?php

namespace Cruddy\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ViewMakeCommand extends GeneratorCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:view {name} {table} {type=index} {inputs?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy view';

    /**
     * The acceptable types of views.
     *
     * @var array
     */
    protected $types = [
        'index',
        'create',
        'show',
        'edit',
    ];

    /**
     * The defaul type of input.
     *
     * @var string
     */
    protected $defaultInput = 'text';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $folder = Config::get('cruddy.frontend_scaffolding') ?? 'default';

        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/views/' . $folder  . '/' . $this->getType() . '.stub');
    }

    /**
     * Get the stub file for the generator.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInputStub(string $input)
    {
        $folder = Config::get('cruddy.default_frontend_scaffolding') ?? 'default';

        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/views/' . $folder  . '/inputs/' . $this->getInput($input) . '.stub');
    }

    /**
     * Get the type of request being created.
     *
     * @return string
     */
    public function getType()
    {
        if (in_array($this->argument('type'), $this->types)) {
            return $this->argument('type');
        }

        return $this->types[0];
    }

    /**
     * Get the input needed.
     *
     * @param  string  $input
     * @return string
     */
    public function getInput(string $input)
    {
        if (array_key_exists($input, Config::get('cruddy.input_defaults'))) {
            return Config::get('cruddy.input_defaults.' . $input);
        }

        return $this->defaultInput;
    }

    /**
     * Get the input needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    public function getInputString(ColumnDefinition $input)
    {
        $inputString = $this->files->get($this->getInputStub($input['type'])) . "\n\t\t";

        $inputString = str_replace(['DummyName', '{{ name }}', '{{name}}'], $input['name'], $inputString);
        $inputString = str_replace(['DummyData', '{{ data }}', '{{data}}'], $this->getExtraInputInfo($input), $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Get the submit input as a string.
     *
     * @return string
     */
    public function getSubmitInputString()
    {
        $inputString = $this->files->get($this->getInputStub('submit'));

        $inputString = str_replace(['DummyValue', '{{ value }}', '{{value}}'], 'Submit', $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Turn the extra column data into a string of rules.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    public function getExtraInputInfo(ColumnDefinition $column)
    {
        $extraInputInfoString = '';

        if ($this->getType() === 'edit' || $this->getType() === 'show') {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }

            $extraInputInfoString .= 'value="{{ $' . $this->getClassName() . '->' . $column["name"] . ' }}"';
        }

        if ($this->getType() === 'show') {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }

            $extraInputInfoString .= 'disabled="disabled"';
        }

        if ($column->unsigned) {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }
            $extraInputInfoString .= 'min="0"';
        }

        if (($column->type == 'boolean' || $column->type == 'tinyInteger') && $column->default == 1) {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }
            $extraInputInfoString .= 'checked="checked"';
        }


        return $extraInputInfoString;
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

        return $this->replaceNamespace($stub, $name)
            ->replaceInputs($stub)
            ->replaceFormAction($stub)
            ->replaceFormEditUrl($stub)
            ->replaceFormCancelUrl($stub)
            ->replaceModelVariable($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceInputs(&$stub)
    {
        $inputs = $this->argument('inputs');

        $hasInput = false;
        $inputsString = '';

        foreach ($inputs as $input) {
            $hasInput = true;
            $inputsString .= $this->getInputString($input);
        }

        if ($hasInput && $this->needsSubmitInput()) {
            $inputsString .= $this->getSubmitInputString();
        }

        $stub = str_replace(['DummyInputs', '{{ inputs }}', '{{inputs}}'], $inputsString, $stub);

        return $this;
    }

    /**
     * Should the view include a submit input.
     *
     * @return boolean
     */
    public function needsSubmitInput()
    {
        return $this->getType() !== 'index' && $this->getType() !== 'show' ;
    }

    /**
     * Replace the model variable for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceModelVariable(&$stub)
    {
        $modelVariable = lcfirst(class_basename($this->argument('name')));

        $stub = str_replace(['DummyModelVariable', '{{ modelVariable }}', '{{modelVariable}}'], $modelVariable, $stub);

        return $this;
    }

    /**
     * Replace the action for the form request.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceFormAction(&$stub)
    {
        $table = $this->argument('table');

        if ($this->getType() === 'create') {
            $stub = str_replace(['DummyAction', '{{ action }}', '{{action}}'], '/' . $table, $stub);
        } else if ($this->getType() === 'edit') {
            $stub = str_replace(['DummyAction', '{{ action }}', '{{action}}'], '/' . $table . '/{{ $' . $this->getClassName() . '->id }}', $stub);
        }

        return $this;
    }

    /**
     * Replace the editUrl for the the edit button.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceFormEditUrl(&$stub)
    {
        $table = $this->argument('table');

        $stub = str_replace(['DummyEditUrl', '{{ editUrl }}', '{{editUrl}}'], '/' . $table . '/{{ $' . $this->getClassName() . '->id }}/edit', $stub);

        return $this;
    }

    /**
     * Replace the cancelUrl for the the cancel button.
     *
     * @param  string  $stub
     * @return $this
     */
    public function replaceFormCancelUrl(&$stub)
    {
        $table = $this->argument('table');

        $stub = str_replace(['DummyCancelUrl', '{{ cancelUrl }}', '{{cancelUrl}}'], '/' . $table, $stub);

        return $this;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = strtolower($name);

        if (config('cruddy.default_frontend_scaffolding') === 'livewire') {
            $name = str_replace('views\\', 'views\\livewire\\', $name);
        }

        return str_replace('\\', '/', $name) . '/' . $this->getType() . '.blade.php';
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
        return $rootNamespace . '\resources\views';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['type', InputArgument::OPTIONAL, 'The type of view'],
            ['inputs', InputArgument::OPTIONAL, 'The inputs for the view'],
            ['name', InputArgument::OPTIONAL, 'The name of the resource'],
            ['table', InputArgument::OPTIONAL, 'The name of the table'],
        ];
    }

    /**
     * Get the class name from the table argument.
     *
     * @return string
     */
    protected function getClassName()
    {
        $table = $this->argument('table');
        return strtolower(Str::studly(Str::singular(trim($table))));
    }
}
