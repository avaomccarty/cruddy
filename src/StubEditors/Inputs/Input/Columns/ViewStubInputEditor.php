<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Illuminate\Database\Schema\ColumnDefinition;

class ViewColumnInputStubEditor extends InputStubEditor
{
    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    protected $vModelNamePlaceholders = [
        'DummyVModelName',
        '{{ vModelName }}',
        '{{vModelName}}'
    ];

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    protected $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
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
     * The acceptable model name placeholders within a stub.
     *
     * @var array
     */
    protected $modelNamePlaceholders = [
        'DummyModelName',
        '{{ ModelName }}',
        '{{ModelName}}'
    ];

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
     * The type for the file.
     *
     * @var string
     */
    protected $type = 'index';

    /**
     * The constructor method.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $column
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(ColumnDefinition $column, string &$stub = '')
    {
        parent::__construct($column, $stub);

        if (is_null($this->column)) {
            $this->setDefaultInput();
        }
    }

    /**
     * Set default column.
     *
     * @return void
     */
    protected function setDefaultInput() : void
    {
        $this->column = new ColumnDefinition([
            'type' => 'submit',
            'name' => 'submit',
            'unsigned' => false,
        ]);
    }

    /**
     * Get the input as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    public function getInputString(string $type = 'index', string $name = '', string $nameOfResource = '') : string
    {
        $this->inputString = $this->getInputAsString();

        $this->replaceInStub($this->modelNamePlaceholders, $this->getReplaceString(), $this->inputString)
            ->replaceInStub($this->namePlaceholders, $this->column['name'], $this->inputString)
            ->replaceInStub($this->valuePlaceholders, $this->getInputValue(), $this->inputString)
            ->replaceInStub($this->vModelNamePlaceholders, $this->column['name'], $this->inputString)
            ->replaceInStub($this->dataPlaceholders, $this->getExtraInputInfo(), $this->inputString);

        return $this->inputString;
    }

    /**
     * Get the input value.
     *
     * @return string
     */
    protected function getInputValue() : string
    {
        return '{{ $' . $this->getCamelCaseSingular($this->getNameOfResource()) . '->' . $this->column['name'] . ' }}';
    }

    /**
     * Set the type.
     *
     * @param  string  $type = 'index'
     * @return void
     */
    public function setType(string $type = 'index') : void
    {
        $this->type = $this->isValidType($type) ? $type : $this->type;
    }

    /**
     * Get the type.
     *
     * @return string
     */
    protected function getType() : string
    {
        return $this->type;
    }

    /**
     * Get the input as a string for the stub.
     *
     * @return string
     */
    protected function getInputAsString() : string
    {
        $file = $this->getStubFile();
        $this->addEndOfLineFormatting($file);

        return $file;
    }

    /**
     * Get the replace string for an input.
     *
     * @return string
     */
    protected function getReplaceString() : string
    {
        if ($this->isVueEditOrShow()) {
            return 'item.' . $this->column['name'];
        }
        
        return $this->column['name'];
    }

    /**
     * Determine if the value should be included within the input. 
     *
     * @return boolean
     */
    protected function shouldAddValueToInput() : bool
    {
        return !$this->needsVueFrontend() && $this->isEditOrShow();
    }

    /**
     * Determine if it is a Vue edit/show file type.
     *
     * @return bool
     */
    protected function isVueEditOrShow() : bool
    {
        return $this->needsVueFrontend() && $this->isEditOrShow();
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

        return in_array($this->type, $types);
    }
        
    /**
     * Turn the extra column data into a string of rules.
     *
     * @return string
     */
    protected function getExtraInputInfo() : string
    {
        $value = '';
        
        foreach ($this->getExtraInputInfoMapping() as $extraInputInfo) {
            if ($extraInputInfo[$this->shouldAddExtraInput]) {
                $this->addExtraInputInfo($value, $extraInputInfo[$this->valueToAdd]);
            }
        }

        return $value;
    }

    /**
     * Get the array for mapping how to add in new extra input strings.
     *
     * @return array
     */
    protected function getExtraInputInfoMapping() : array
    {
        return [
            [
                $this->shouldAddExtraInput => $this->shouldAddValueToInput(),
                $this->valueToAdd => $this->getValueFromColumn()
            ],
            [
                $this->shouldAddExtraInput => $this->isShowType(),
                $this->valueToAdd => $this->inputDisabledString
            ],
            [
                $this->shouldAddExtraInput => $this->column->unsigned,
                $this->valueToAdd => $this->unsignedExtraInputInfo
            ],
            [
                $this->shouldAddExtraInput => $this->shouldInputBeChecked(),
                $this->valueToAdd => $this->inputCheckedString
            ],
        ];
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
     * Get the value from the column.
     *
     * @return string
     */
    protected function getValueFromColumn() : string
    {
        return 'value="' . $this->getInputValue() . '"';
    }

    /**
     * Add the value from the column, if necessary.
     *
     * @param  string  &$value
     * @param  string  $valueToAdd
     * @return void
     */
    protected function addExtraInputInfo(string &$value, string $valueToAdd) : void
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
     * Determine if the type is show.
     *
     * @return boolean
     */
    protected function shouldInputBeChecked() : bool
    {
        return $this->isBooleanColumn() && $this->column->default == 1;
    }

    /**
     * Determine if the column is a boolean column.
     *
     * @return boolean
     */
    protected function isBooleanColumn() : bool
    {
        return $this->column->type === 'boolean' || $this->column->type === 'tinyInteger';
    }
}