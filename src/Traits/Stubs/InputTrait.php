<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\File;

trait InputTrait
{
    use ConfigTrait, VariableTrait;

    /**
     * The acceptable input placeholders within a stub.
     *
     * @var array
     */
    protected $stubInputPlaceholders = [
        'DummyInputs',
        '{{ inputs }}',
        '{{inputs}}'
    ];

    /**
     * The acceptable name placeholders within a stub.
     *
     * @var array
     */
    protected $stubNamePlaceholders = [
        'DummyName',
        '{{ name }}',
        '{{name}}'
    ];

    /**
     * The acceptable data placeholders within a stub.
     *
     * @var array
     */
    protected $stubDataPlaceholders = [
        'DummyData',
        '{{ data }}',
        '{{data}}'
    ];


    /**
     * Get the default input type.
     *
     * @return string
     */
    protected function getDefaultInput() : string
    {
        return 'text';
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceInputs(string &$stub) : self
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

        $stub = str_replace($this->stubInputPlaceholders, $inputsString, $stub);

        return $this;
    }

    /**
     * Get the input needed.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInput(string $input) : string
    {
        if (array_key_exists($input, $this->getInputDefaults())) {
            return $this->getDefaultInputType($input);
        }

        return $this->getDefaultInput();
    }

    /**
     * Get the input needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getInputString(ColumnDefinition $input) : string
    {
        $inputString = File::get($this->getInputStub($input['type'])) . "\n\t\t";

        if (($this->getType() === 'edit' || $this->getType() === 'show') && $this->needsVueFrontend()) {
            $inputString = str_replace($this->stubModelNamePlaceholders, 'item.' . $input['name'], $inputString);
        } else {
            $inputString = str_replace($this->stubModelNamePlaceholders, $input['name'], $inputString);
        }

        $inputString = str_replace($this->stubNamePlaceholders, $input['name'], $inputString);
        $inputString = str_replace($this->stubDataPlaceholders, $this->getExtraInputInfo($input), $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Get the submit input as a string.
     *
     * @return string
     */
    protected function getSubmitInputString() : string
    {
        $inputString = File::get($this->getInputStub('submit'));
        $inputString = str_replace($this->stubValuePlaceholders, 'Submit', $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Get the stub input file.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInputStub(string $input) : string
    {
        $frontendScaffolding = $this->getFrontendScaffoldingName();
        $stubsLocation = $this->getStubsLocation();

        return $this->resolveStubPath($stubsLocation . '/views/' . $frontendScaffolding  . '/inputs/' . $this->getInput($input) . '.stub');
    }
        
    /**
     * Turn the extra column data into a string of rules.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    protected function getExtraInputInfo(ColumnDefinition $column) : string
    {
        $extraInputInfoString = '';

        if (($this->getType() === 'edit' || $this->getType() === 'show') && !$this->needsVueFrontend()) {
            if (strlen($extraInputInfoString) > 0) {
                $extraInputInfoString .= ' ';
            }

            $extraInputInfoString .= 'value="{{ $' . $this->getCamelCaseSingular($this->argument('name')) . '->' . $column["name"] . ' }}"';
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
     * Should the view include a submit input.
     *
     * @return boolean
     */
    protected function needsSubmitInput() : bool
    {
        return $this->getType() !== 'index' && $this->getType() !== 'show';
    }
}