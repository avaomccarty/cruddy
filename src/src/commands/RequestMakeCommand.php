<?php

namespace Cruddy\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class RequestMakeCommand extends GeneratorCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:request {name} {type=update} {rules?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy request class';

    /**
     * The acceptable types of requests.
     *
     * @var array
     */
    protected $types = [
        'update',
        'store'
    ];

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/request.stub');
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

        return $this->replaceNamespace($stub, $name)->replaceRules($stub)->replaceClass($stub, $name);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $type = ucfirst(strtolower($this->argument('type')));
        $newName = $type . $this->argument('name');

        return trim($newName);
    }

    /**
     * Replace the rules for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceRules(&$stub)
    {
        $rules = $this->argument('rules');

        $hasRule = false;
        $rulesString = '';

        foreach ($rules as $rule) {
            $hasRule = true;
            if ($rule->name !== 'id') {
                $validationRules = '';
                $this->addDefaultValidationRules($rule->type, $validationRules);
                $this->addColumnValidationRules($rule, $validationRules);

                $rulesString .= "'$rule->name' => '$validationRules',\n\t\t\t";
            }
        }
        if ($hasRule) {
            // Remove extra line break and tabs
            $rulesString = substr($rulesString, 0, -4);
        }

        $stub = str_replace(['DummyRules', '{{ rules }}', '{{rules}}'], $rulesString, $stub);

        return $this;
    }

    /**
     * Add the specific column validation rules to the validationRules string.
     *
     * @param  string  $column
     * @param  string  $validationRules
     * @return void
     */
    public function addColumnValidationRules(ColumnDefinition $column, string &$validationRules = '')
    {
        if ($column->unsigned) {
            if (strpos($validationRules, 'min:') === false) {
                if (strlen(trim($validationRules)) > 0) {
                    $validationRules .= '|';
                }

                $validationRules .= 'min:0';
            }
            if (strpos($validationRules, 'integer') === false) {
                if (strlen(trim($validationRules)) > 0) {
                    $validationRules .= '|';
                }

                $validationRules .= 'integer';
            }
        }

        if ($column->nullable) {
            if (strpos($validationRules, 'nullable') === false) {
                if (strlen(trim($validationRules)) > 0) {
                    $validationRules = 'nullable|' . $validationRules;
                } else {
                    $validationRules = 'nullable';
                }
            }
        }
    }

    /**
     * Add the default column type validation rules to the validationRules string.
     *
     * @param  string  $type
     * @param  string  $validationRules
     * @return void
     */
    public function addDefaultValidationRules(string $type = 'string', string &$validationRules = '')
    {
        if (strlen(trim(Config::get('cruddy.validation_defaults.' . $type))) > 0) {
            if (strlen(trim($validationRules)) > 0) {
                $validationRules .= '|';
            }

            $validationRules .= Config::get('cruddy.validation_defaults.' . $type);
        }
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
        return $rootNamespace.'\Http\Requests';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['type', InputArgument::OPTIONAL, 'The type of request class'],
            ['rules', InputArgument::OPTIONAL, 'The validation rules of request class'],
        ];
    }
}
