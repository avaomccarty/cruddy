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
     * The value for the submit input.
     *
     * @var string
     */
    protected $submitValue = 'Submit';

    /**
     * The key for should add extra input info in array.
     *
     * @var string
     */
    protected $shouldAddExtraInput = 'shouldAddExtraInput';

    /**
     * The key for value to add in array.
     *
     * @var string
     */
    protected $valueToAdd = 'valueToAdd';

    /**
     * The value for disabling an input.
     *
     * @var string
     */
    protected $inputDisabledString = 'disabled="disabled"';

    /**
     * The value for checked inputs.
     *
     * @var string
     */
    protected $inputCheckedString = 'checked="checked"';

    /**
     * The extra info needed for an unsigned input.
     *
     * @var string
     */
    protected $unsignedExtraInputInfo = 'min="0"';

    /**
     * Should the view include a submit input.
     *
     * @return boolean
     */
    protected function typeNeedsSubmitInput() : bool
    {
        return $this->getType() !== 'index' && $this->getType() !== 'show';
    }

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
     * Get the inputs.
     *
     * @return array
     */
    protected function getInputs() : array
    {
        return $this->argument('inputs') ?? [];
    }

    /**
     * Replace the inputs for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceInputs(string &$stub) : self
    {
        $stub = str_replace($this->stubInputPlaceholders, $this->getInputsString(), $stub);

        return $this;
    }

    /**
     * Get the inputs as a string.
     *
     * @return string
     */
    protected function getInputsString() : string
    {
        $inputsString = '';
        $inputs = $this->getInputs();

        foreach ($inputs as $key => $input) {
            $inputsString .= $this->getInputString($input);

            if ($key === array_key_last($inputs) && $this->typeNeedsSubmitInput()) {
                $inputsString .= $this->getSubmitInputString();
            }
        }

        return $inputsString;
    }

    /**
     * Get the input.
     *
     * @param  string  $inputType
     * @return string
     */
    protected function getInput(string $inputType) : string
    {
        if (array_key_exists($inputType, $this->getInputDefaults())) {
            return $this->getDefaultForInputType($inputType);
        }

        return $this->getDefaultInput();
    }

    /**
     * Get the input file.
     *
     * @param  string  $type
     * @return string
     */
    protected function getInputFile(string $type) : string
    {
        return File::get($this->getInputStub($type));
    }

    /**
     * Get the input needed as a string.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    protected function getInputString(ColumnDefinition $column) : string
    {
        $inputString = $this->getInputAsString($column);
        $replaceString = $this->getReplaceString($column);

        $inputString = str_replace($this->stubModelNamePlaceholders, $replaceString, $inputString);
        $inputString = str_replace($this->stubNamePlaceholders, $column['name'], $inputString);
        $inputString = str_replace($this->stubDataPlaceholders, $this->getExtraInputInfo($column), $inputString);

        return str_replace('  ', ' ', $inputString);
    }

    /**
     * Get the replace string for an input.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    protected function getReplaceString(ColumnDefinition $column)
    {
        if ($this->isEditOrShow() && $this->needsVueFrontend()) {
            return 'item.' . $column['name'];
        }
        
        return $column['name'];
    }

    /**
     * Get the input as a string for the stub.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    protected function getInputAsString(ColumnDefinition $column) : string
    {
        return $this->getInputFile($column['type']) . "\n\t\t";
    }

    /**
     * Determine if the resource is of the edit or show type.
     *
     * @return boolean
     */
    protected function isEditOrShow()
    {
        $type = $this->getType();
        return $type === 'edit' || $type === 'show';
    }

    /**
     * Get the submit input as a string.
     *
     * @return string
     */
    protected function getSubmitInputString() : string
    {
        $inputString = str_replace($this->stubValuePlaceholders, $this->submitValue, $this->getInputFile('submit'));

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
        return $this->resolveStubPath($this->getInputStubLocation($input));
    }

    /**
     * Get the input stub file locaiton.
     *
     * @param  string  $input
     * @return string
     */
    protected function getInputStubLocation(string $input) : string
    {
        $frontendScaffolding = $this->getFrontendScaffoldingName();
        $stubsLocation = $this->getStubsLocation();

        return $stubsLocation . '/views/' . $frontendScaffolding  . '/inputs/' . $this->getInput($input) . '.stub';
    }
        
    /**
     * Turn the extra column data into a string of rules.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    protected function getExtraInputInfo(ColumnDefinition $column) : string
    {
        $value = '';
        
        foreach ($this->getExtraInputInfoMapping($column) as $mapping) {
            if ($mapping[$this->shouldAddExtraInput]) {
                $this->addExtraInputInfo($value, $mapping[$this->valueToAdd]);
            }
        }

        return $value;
    }

    /**
     * Get the array for mapping how to add in new extra input strings.
     *
     * @param  ColumnDefinition  $column
     * @return array
     */
    protected function getExtraInputInfoMapping(ColumnDefinition $column) : array
    {
        return [
            [
                $this->shouldAddExtraInput => $this->shouldAddValueToInput(),
                $this->valueToAdd => $this->getValueFromColumn($column)
            ],
            [
                $this->shouldAddExtraInput => $this->isShowType(),
                $this->valueToAdd => $this->inputDisabledString
            ],
            [
                $this->shouldAddExtraInput => $column->unsigned,
                $this->valueToAdd => $this->unsignedExtraInputInfo
            ],
            [
                $this->shouldAddExtraInput => $this->shouldInputBeChecked($column),
                $this->valueToAdd => $this->inputCheckedString
            ],
        ];
    }

    /**
     * Get the value from the column.
     *
     * @param  ColumnDefinition  $column
     * @return string
     */
    protected function getValueFromColumn(ColumnDefinition $column)
    {
        return 'value="{{ $' . $this->getCamelCaseSingular($this->argument('name')) . '->' . $column['name'] . ' }}"';
    }

    /**
     * Add the value from the column, if necessary.
     *
     * @param  string  &$value
     * @param  string  $valueToAdd
     * @return void
     */
    protected function addExtraInputInfo(string &$value, $valueToAdd) : void
    {
        if (strlen($value) > 0) {
            $this->prepString($value);
        }
        $value .= $valueToAdd;
    }

    /**
     * Prep the string for the next input string.
     *
     * @param  string  &$value
     * @return void
     */
    protected function prepString(string &$value) : void
    {
        $value .= ' ';
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
     * Determine if the type is show.
     *
     * @return boolean
     */
    protected function isShowType() : bool
    {
        return $this->getType() === 'show';
    }

    /**
     * Determine if the type is show.
     *
     * @param  ColumnDefinition  $column
     * @return boolean
     */
    protected function shouldInputBeChecked(ColumnDefinition $column) : bool
    {
        return $this->isBooleanColumn($column) && $column->default == 1;
    }

    /**
     * Determine if the column is a boolean column.
     *
     * @param  ColumnDefinition  $column
     * @return boolean
     */
    protected function isBooleanColumn(ColumnDefinition $column) : bool
    {
        return $column->type === 'boolean' || $column->type === 'tinyInteger';
    }
}