<?php

namespace Cruddy\StubEditors\Inputs;

use Cruddy\ForeignKeyDefinition;
use Cruddy\ForeignKeyValidation\ForeignKeyValidation;
use Cruddy\StubEditors\Inputs\Input\StubInputEditor;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Fluent;

class StubInputsEditor extends StubInputEditor
{
    /**
     * The file type.
     *
     * @var string
     */
    protected $fileType = 'controller';

    /**
     * The file types.
     *
     * @var array
     */
    protected $fileTypes = [
        'controller',
        'view',
        'request',
    ];

    /**
     * The columns
     *
     * @var array
     */
    protected $columns = [];

    /**
     * The stub.
     *
     * @var string
     */
    protected $stub = '';

    /**
     * The constructor method.
     *
     * @param  array  $columns
     * @param  string  $fileType = ''
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(array $columns, string $fileType = '', string &$stub = '')
    {
        $this->columns = $columns;
        $this->setFileType($fileType);
        $this->stub = $stub ?? '';
    }

    /**
     * Set the file type.
     *
     * @param  string  $fileType
     * @return void
     */
    protected function setFileType(string $fileType) : void
    {
        $this->fileType = $this->isValidFileType($fileType) ? $fileType : $this->fileTypes[0];
    }

    /**
     * Determine if the file type is valid.
     *
     * @param  string  $fileType
     * @return boolean
     */
    protected function isValidFileType(string $fileType) : bool
    {
        return in_array($fileType, $this->fileTypes);
    }

    /**
     * Get a validation rule string from a foreignKey.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return string
     */
    protected function getForeignKeyValidation(ForeignKeyDefinition $foreignKey) : string
    {
        return App::make(ForeignKeyValidation::class, [$foreignKey])
            ->getForeignKeyValidation();
    }

    /**
     * Get the inputs as a string.
     *
     * @param  string  $type = 'index'
     * @param  string  $name = ''
     * @return string
     */
    public function getInputString(string $type = 'index', string $name = '') : string
    {
        $columnDefinitions = $this->getColumnDefinitions();
        foreach ($columnDefinitions as $column) {
            $inputStubEditor = App::make(StubInputEditor::class, [$column, $this->fileType, $this->stub, $this->fileTypeNeedsSubmitInput($type)]);
            $inputStubEditor->setForeignKeys($this->getForeignKeys());
            $this->inputString .= $inputStubEditor->getInputString($type, $name);
        }

        if ($this->shouldHaveSubmitButton($type)) {
            $inputStubEditor = App::make(StubInputEditor::class, [null, $this->fileType, $this->stub, $this->fileTypeNeedsSubmitInput($type)]);
            $inputStubEditor->setForeignKeys($this->getForeignKeys());
            $this->inputString .= $inputStubEditor->getInputString($type, $name);
        }

        if (count($this->columns) > 0) {
            $inputStubEditor->removeEndOfLineFormatting($this->inputString);
        }

        return $this->inputString;
    }

    /**
     * Determine if the file should have a submit button.
     *
     * @param  string  $type = 'index'
     * @return boolean
     */
    protected function shouldHaveSubmitButton(string $type = 'index') : bool
    {
        return in_array($type, [
            'create',
            'edit',
        ]);
    }

    /**
     * Determine if the rule is for a foreign key.
     *
     * @param  \Illuminate\Support\Fluent  $rule
     * @return boolean
     */
    protected function isAColumnDefinition(Fluent $rule) : bool
    {
        return is_a($rule, ColumnDefinition::class);
    }

    /**
     * Get the foreign keys from the columns.
     *
     * @return array
     */
    protected function getColumnDefinitions() : array
    {
        return array_filter($this->columns, function ($column) {
            return $this->isAColumnDefinition($column);
        });
    }

    /**
     * Determine if the rule is for a foreign key.
     *
     * @param  \Illuminate\Support\Fluent  $rule
     * @return boolean
     */
    protected function isAForeignKey(Fluent $rule) : bool
    {
        return is_a($rule, ForeignKeyDefinition::class);
    }

    /**
     * Get the foreign keys from the columns.
     *
     * @return array
     */
    protected function getForeignKeys() : array
    {
        return array_filter($this->columns, function ($column) {
            return $this->isAForeignKey($column);
        });
    }

    /**
     * Should the stub include a submit input.
     *
     * @param  string  $type
     * @return boolean
     */
    protected function fileTypeNeedsSubmitInput(string $type) : bool
    {
        return $this->fileType === 'view' && in_array($type, [
            'edit',
            'create',
            'page',
        ]);
    }
}