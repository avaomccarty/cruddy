<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class RequestMakeCommand extends GeneratorCommand
{
    use CommandTrait;

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
        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/request.stub');
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
            ->replaceRules($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $type = Str::ucfirst(strtolower(trim($this->getType())));
        $name = $this->argument('name') ?? '';

        return $type . $name;
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
