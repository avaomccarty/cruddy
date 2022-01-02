<?php

namespace Cruddy\Traits;

use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Cruddy\StubEditors\StubEditor;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait CommandTrait
{
    use ConfigTrait;

    /**
     * Get an argument from the command.
     *
     * @param  string  $argument
     * @return mixed
     */
    protected function getArgument(string $argument) : mixed
    {
        if (method_exists(self::class, 'argument')) {
            return $this->argument($argument);
        }

        return null;
    }

    /**
     * Get an option from the command.
     *
     * @param  string  $option
     * @return mixed
     */
    protected function getOption(string $option) : mixed
    {
        if (method_exists(self::class, 'option')) {
            return $this->option($option);
        }

        return null;
    }

    /**
     * Get the type.
     *
     * @return string
     */
    protected function getType() : string
    {
        return (string)$this->getArgument('type') ?? '';
    }

    /**
     * Get the type option.
     *
     * @return string
     */
    protected function getTypeOption() : string
    {
        return (string)$this->getOption('type') ?? '';
    }

    /**
     * Get the table.
     *
     * @return string
     */
    protected function getTableName() : string
    {
        return (string)$this->getArgument('table') ?? '';
    }

    /**
     * Get the name.
     *
     * @return string
     */
    protected function getResourceName() : string
    {
        return (string)$this->getArgument('name') ?? '';
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
     * Get the lower plural version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getLowerPlural(string $value) : string
    {
        return strtolower(Str::pluralStudly($value)) ?? '';
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
     * Get the studly singular version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCaseSingular(string $value) : string
    {
        return lcfirst(Str::studly(Str::singular(trim($value)))) ?? '';
    }

    /**
     * Get the studly plural version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCasePlural(string $value) : string
    {
        return lcfirst(Str::pluralStudly(trim($value))) ?? '';
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
     * Get the name string.
     *
     * @return string
     */
    protected function getNameString() : string
    {
        return (string)$this->getArgument('name') ?? '';
    }

    /**
     * Get the inputs.
     *
     * @return array
     */
    protected function getInputs() : array
    {
        return (array)$this->getArgument('inputs') ?? [];
    }

    /**
     * Get the inputs.
     *
     * @return array
     */
    protected function getInputsOption() : array
    {
        return (array)$this->option('inputs') ?? [];
    }

    /**
     * Get the commands.
     *
     * @return array
     */
    protected function getCommands() : array
    {
        return (array)$this->getOption('commands') ?? [];
    }

    /**
     * Get the keys.
     *
     * @return array
     */
    protected function getKeys() : array
    {
        return (array)$this->getArgument('keys') ?? [];
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules() : array
    {
        return (array)$this->getArgument('rules') ?? [];
    }

    /**
     * Determine if the resource should be an API.
     *
     * @return boolean
     */
    public function getApi() : bool
    {
        return (bool)$this->option('api') ?? false;
    }

    /**
     * Get the model.
     *
     * @return string
     */
    protected function getModel() : string
    {
        return (string)$this->option('model') ?? '';
    }

    /**
     * Set the stub editor.
     *
     * @param  string  $type = 'controller'
     * @return void
     */
    protected function setStubEditor(string $type = 'controller') : void
    {
        $this->stubEditor = App::make(StubEditor::class, [$type]);
    }

    /**
     * Set the stub editor.
     *
     * @param  string  $type = 'controller'
     * @return \Cruddy\StubEditors\Inputs\StubInputsEditor
     */
    protected function getStubInputsEditor(string $type = 'controller') : StubInputsEditor
    {
        return App::make(StubInputsEditor::class, [$this->getInputsOption(), $type]);
    }

    /**
     * Get all the placeholders for this stub.
     *
     * @return array
     */
    protected function getAllPlaceholders() : array
    {
        $allPlaceholders = [];

        foreach ($this->placeholderArrays as $placeholderArray) {
            foreach ($this->$placeholderArray as $placeholder) {
                $allPlaceholders[] = $placeholder;
            }
        }

        return $allPlaceholders;
    }
}
