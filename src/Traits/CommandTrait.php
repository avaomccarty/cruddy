<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\InputTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait CommandTrait
{
    use InputTrait, ModelTrait, ConfigTrait;

    /**
     * Get the table.
     *
     * @return string
     */
    protected function getTableName() : string
    {
        return (string)$this->argument('table') ?? '';
    }

    /**
     * Get the lower singular version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getLowerSingular(string $value) : string
    {
        return strtolower($this->getStudlySingular($value)) ?? '';
    }

    /**
     * Get the studly singular version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getStudlySingular(string $value) : string
    {
        return Str::studly(Str::singular(trim($value))) ?? '';
    }

    /**
     * Get the type argument.
     *
     * @return string
     */
    protected function getType() : string
    {
        $type = $this->argument('type') ?? '';
        if (isset($this->types) && is_array($this->types)) {
            return $this->isValidType($type, $this->types) ? $type : $this->types[0];
        }

        if ($type) {
            if ($this->isValidType($type, $this->getDefaultTypes())) {
                return $type;
            }
        }

        return $this->getDefaultTypes()[0];
    }

    /**
     * Determine if the type is valid.
     *
     * @param  string  $type
     * @param  array  $types
     * @return boolean
     */
    protected function isValidType(string $type, array $types = []) : bool
    {
        return in_array($type, $types);
    }

    /**
     * Get the default types.
     *
     * @return array
     */
    protected function getDefaultTypes() : array
    {
        return [
            'index',
            'create',
            'show',
            'edit',
         ];
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStub() : string
    {
        $frontendScaffolding = $this->getFrontendScaffoldingName();
        $stubsLocation = $this->getStubsLocation();

        return $this->resolveStubPath($stubsLocation . '/views/' . $frontendScaffolding  . '/' . $this->getType() . '.stub');
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStubFile() : string
    {
        return $this->files->get($this->getStub());
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
            : dirname(__DIR__) . '/Commands/' . $stub;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    protected function getNameString() : string
    {
        return (string)$this->argument('name') ?? '';
    }
}
