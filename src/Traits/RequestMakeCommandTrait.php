<?php

namespace Cruddy\Traits;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

trait RequestMakeCommandTrait
{
    use CommandTrait;

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
     * Get the type of request.
     *
     * @return string
     */
    protected function getType() : string
    {
        if (method_exists(self::class, 'argument')) {
            return (string)$this->argument('type');
        }

        return $this->types[0];
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput() : string
    {
        $name = $this->getStudlySingular($this->getType());
        if (method_exists(self::class, 'argument')) {
            $name .= $this->getStudlySingular($this->argument('name'));
            return $name;
        }

        $name .= $this->getStudlySingular($this->name);
        return $name ?? '';
    }

    /**
     * Add the default column type validation rules to the validationRules string.
     *
     * @param  string  $type
     * @param  string  $validationRules
     * @return void
     */
    protected function addDefaultValidationRules(string $type = 'string', string &$validationRules = '') : void
    {
        $defaults = Config::get('cruddy.validation_defaults.' . $type);

        if (strlen(trim($defaults)) > 0) {
            if (strlen(trim($validationRules)) > 0) {
                $validationRules .= '|';
            }

            $validationRules .= $defaults;
        }
    }

    /**
     * Add the specific column validation rules to the validationRules string.
     *
     * @param  ColumnDefinition  $column
     * @param  string  $validationRules
     * @return void
     */
    protected function addColumnValidationRules(ColumnDefinition $column, string &$validationRules = '') : void
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
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/request.stub');
    }
        
    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub) : string
    {
        return File::exists($customPath = base_path(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }
}
