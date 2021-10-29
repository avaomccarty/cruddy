<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\RuleTrait;
use Illuminate\Support\Str;

trait CommandTrait
{
    use ConfigTrait, RuleTrait;

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
     * Get the name.
     *
     * @return string
     */
    protected function getResourceName() : string
    {
        return (string)$this->argument('name') ?? '';
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
     * Get the inputs as a string.
     *
     * @param  boolean  $needsSubmitInput
     * @return string
     */
    protected function getInputsString(bool $needsSubmitInput = false) : string
    {
        $inputsString = '';
        $inputs = $this->getInputs();

        foreach ($inputs as $key => $input) {
            $inputsString .= $this->getInputString($input, $this->isVueEditOrShow());

            if ($key === array_key_last($inputs) && $needsSubmitInput) {
                $submitInputFile = $this->getInputFile('submit');
                $this->replaceInStub($this->valuePlaceholders, 'Submit', $submitInputFile);
                $inputsString .= $submitInputFile;
            }
        }

        return $inputsString;
    }

    /**
     * Should the view include a submit input.
     *
     * @return boolean
     */
    protected function typeNeedsSubmitInput() : bool
    {
        $type = $this->getType();

        return $type !== 'index' && $type !== 'show';
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
     * Get the name string.
     *
     * @return string
     */
    protected function getNameString() : string
    {
        return (string)$this->argument('name') ?? '';
    }

    /**
     * Get the inputs.
     *
     * @return array
     */
    protected function getInputs() : array
    {
        return (array)$this->argument('inputs') ?? [];
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules() : array
    {
        return (array)$this->argument('rules') ?? [];
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
     * Determine if the resource is of the edit or show type.
     *
     * @return boolean
     */
    protected function isEditOrShow() : bool
    {
        $types = [
            'edit',
            'show',
        ];

        return in_array($this->getType(), $types);
    }

    /**
     * Determine if the value should be included within the input. 
     *
     * @return boolean
     */
    protected function shouldAddValueToInput() : bool
    {
        return $this->isEditOrShow() && !$this->needsVueFrontend();
    }

    /**
     * Determine if it is a Vue edit/show file type.
     *
     * @return bool
     */
    protected function isVueEditOrShow() : bool
    {
        return $this->isEditOrShow() && $this->needsVueFrontend();
    }
}
