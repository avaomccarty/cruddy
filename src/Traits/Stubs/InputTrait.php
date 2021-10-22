<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\File;

trait InputTrait
{
    use ConfigTrait, VariableTrait, StubTrait;

    /**
     * The acceptable input placeholders within a stub.
     *
     * @var array
     */
    protected $inputPlaceholders = [
        'DummyInputs',
        '{{ inputs }}',
        '{{inputs}}'
    ];

    /**
     * The acceptable name placeholders within a stub.
     *
     * @var array
     */
    protected $namePlaceholders = [
        'DummyName',
        '{{ name }}',
        '{{name}}'
    ];

    /**
     * The acceptable data placeholders within a stub.
     *
     * @var array
     */
    protected $dataPlaceholders = [
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
        $type = $this->getType();

        return $type !== 'index' && $type !== 'show';
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
     * @return mixed
     */
    protected function getInputs() : mixed
    {
        return $this->argument('inputs') ?? [];
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
                $submitInputFile = $this->getInputFile('submit');
                $this->replaceInStub($this->valuePlaceholders, 'Submit', $submitInputFile);
                $inputsString .= $submitInputFile;
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

        $this->replaceInStub($this->modelNamePlaceholders, $replaceString, $inputString)
            ->replaceInStub($this->namePlaceholders, $column['name'], $inputString)
            ->replaceInStub($this->dataPlaceholders, $this->getExtraInputInfo($column), $inputString);

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
        return $this->getInputFile($column['type']) . $this->getEndOfLine();
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
        
        foreach ($this->getExtraInputInfoMapping($column) as $extraInputInfo) {
            if ($extraInputInfo[$this->shouldAddExtraInput]) {
                $this->addExtraInputInfo($value, $extraInputInfo[$this->valueToAdd]);
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

    /**
     * Get the input string for the controller.
     *
     * @return string
     */
    protected function getControllerInputsString() : string
    {
        $inputs = $this->option('inputs') ?? [];
        $inputsString = '';

        foreach ($inputs as $input) {
            $inputsString .= $this->getControllerInputString($input);
        }

        $this->removeEndOfLineFormatting($inputsString);

        return $inputsString;
    }

    /**
     * Get the input needed as a string for the controller.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getControllerInputString(ColumnDefinition $input) : string
    {
        if ($this->isIdColumn($input)) {
            return '';
        }

        return "'" . $input->name . "'" . ' => $request->' . $input->name . "," . $this->getEndOfLine();
    }

    /**
     * Get the input needed as a string for the model.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getModelInputString(ColumnDefinition $input) : string
    {
        if ($this->isIdColumn($input)) {
            return '';
        }

        return "'" . $input->name . "'," . $this->getEndOfLine();
    }

    /**
     * Determine if the input is for an ID.
     *
     * @param  ColumnDefinition  $input
     * @return boolean
     */
    protected function isIdColumn(ColumnDefinition $input) : bool
    {
        return $input->name === 'id';
    }
}