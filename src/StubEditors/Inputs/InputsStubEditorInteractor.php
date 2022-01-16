<?php

namespace Cruddy\StubEditors\Inputs;

use Cruddy\FluentInteractor;
use Cruddy\StubEditors\StubEditor;
use Illuminate\Support\Facades\App;
use Cruddy\StubEditors\Inputs\ColumnInputsStubEditor;

class InputsStubEditorInteractor extends StubEditor
{
    /**
     * The name of the resource.
     *
     * @var string
     */
    protected $nameOfResource = '';

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
     * The column inputs stub editor.
     *
     * @var \Cruddy\StubEditors\Inputs\ColumnInputsStubEditor;
     */
    protected $columnInputsStubEditor;

    /**
     * The foreign key inputs stub editor.
     *
     * @var \Cruddy\StubEditors\Inputs\ForeignKeyInputsStubEditor;
     */
    protected $foreignKeyInputsStubEditor;

    /**
     * The column definitions.
     *
     * @var array
     */
    protected $columnDefinitions = [];

    /**
     * The foreign keys.
     *
     * @var array
     */
    protected $foreignKeys = [];

    /**
     * The constructor method.
     *
     * @param  array  $columns
     * @param  string  $fileType
     * @param  string  $stub
     * @return void
     */
    public function __construct(protected array $columns, protected string $fileType, protected string $stub)
    {
        $this->setFileType($fileType);
        $this->setColumnDefinitions();
        $this->setForeignKeys();
        $this->setColumnInputsStubEditor();
        $this->setForeignKeyInputsStubEditor();
    }

    /**
     * Update the stub with the input strings.
     *
     * @return string
     */
    public function getUpdatedStub() : string
    {
        $this->replaceInStub($this->inputPlaceholders, $this->getInputStrings());

        return $this->stub;
    }

    /**
     * Get the combined input strings.
     *
     * @return string
     */
    public function getInputStrings() : string
    {
        return $this->columnInputsStubEditor->getInputString() . $this->foreignKeyInputsStubEditor->getInputString();
    }
    
    /**
     * Set the foreign key stub editor. 
     *
     * @return void
     */
    protected function setForeignKeyInputsStubEditor() : void
    {
        $this->foreignKeyInputsStubEditor = App::make(ForeignKeyInputsStubEditor::class, [
            $this->getForeignKeys(),
            $this->fileType,
            $this->stub,
        ])
            ->setNameOfResource($this->nameOfResource);
    }
    
    /**
     * Set the column inputs stub editor. 
     *
     * @return void
     */
    protected function setColumnInputsStubEditor() : void
    {
        $this->columnInputsStubEditor = App::make(ColumnInputsStubEditor::class, [
            $this->getColumnDefinitions(),
            $this->fileType,
            $this->stub,
            $this->foreignKeys,
        ])
            ->setNameOfResource($this->nameOfResource);
    }

    /**
     * Set the foreign keys.
     *
     * @return void
     */
    protected function setForeignKeys() : void
    {
        $this->foreignKeys = array_filter($this->columns, function ($column) {
            return FluentInteractor::isAForeignKey($column);
        });
    }

    /**
     * Set the column definitions.
     *
     * @return void
     */
    protected function setColumnDefinitions() : void
    {
        $this->columnDefinitions = array_filter($this->columns, function ($column) {
            return FluentInteractor::isAColumnDefinition($column);
        });
    }

    /**
     * Set the file type.
     *
     * @param  string  $fileType
     * @return void
     */
    protected function setFileType(string $fileType) : void
    {
        $this->fileType = $this->isValidFileType($fileType) ? $fileType : $this->getDefaultFileType();
    }

    /**
     * Get the default file type.
     *
     * @return string
     */
    protected function getDefaultFileType() : string
    {
        return $this->fileTypes[0];
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
     * Get the column definitions.
     *
     * @return array
     */
    protected function getColumnDefinitions() : array
    {
        return $this->columnDefinitions;
    }

    /**
     * Get the foreign keys.
     *
     * @return array
     */
    protected function getForeignKeys() : array
    {
        return $this->foreignKeys;
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        return $this->stub;
    }

    /**
     * Set the name of the resource.
     *
     * @param  string  $name
     * @return self
     */
    public function setNameOfResource(string $name) : self
    {
        $this->nameOfResource = $name;

        return $this;
    }
}