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
        if (isset($this->types) && is_array($this->types)) {
            if (in_array($this->argument('type'), $this->types)) {
                return $this->argument('type');
            }

            return $this->types[0];
        }

        if ($this->argument('type')) {
            if (in_array($this->argument('type'), $this->getDefaultTypes())) {
                return $this->argument('type');
            }
        }

        return $this->getDefaultTypes()[0];
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
}
