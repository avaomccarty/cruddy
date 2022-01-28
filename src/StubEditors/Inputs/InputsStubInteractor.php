<?php

namespace Cruddy\StubEditors\Inputs;

use Cruddy\Fluent\FluentInteractor;
use Cruddy\StubEditors\CollectionStub;
use Illuminate\Support\Facades\App;
use Cruddy\StubEditors\Inputs\Input\ForeignKeys\ForeignKeyInputStub;

abstract class InputsStubInteractor extends CollectionStub
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
     * @var string[]
     */
    protected $fileTypes = [
        'controller',
        'view',
        'request',
    ];

    /**
     * The column inputs stub editor.
     *
     * @var \Cruddy\StubEditors\Inputs\ColumnInputsStub;
     */
    protected $columnInputsStubEditor;

    /**
     * The foreign key inputs stub editor.
     *
     * @var \Cruddy\StubEditors\Inputs\ForeignKeyInputsStub;
     */
    protected $foreignKeyInputsStubEditor;

    /**
     * The column definitions.
     *
     * @var \Illuminate\Database\Schema\ColumnDefinition[]
     */
    protected $columnDefinitions = [];

    /**
     * The foreign keys.
     *
     * @var \Cruddy\ForeignKeyDefinition[]
     */
    protected $foreignKeys = [];
        
    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = ',';

    /**
     * The collection of stubs.
     *
     * @var string[]
     */
    protected $stubs = [
        ColumnInputsStub::class,
        ForeignKeyInputsStub::class,
    ];

    /**
     * The constructor method.
     *
     * @param  array  $columns
     * @param  string  $fileType
     * @return void
     */
    public function __construct(protected array $columns, protected string $fileType)
    {
        $this->setFileType($fileType);
        $this->setColumnDefinitions();
        $this->setForeignKeys();

        parent::__construct($this->stubs);
    }

    /**
     * Set the column definitions.
     *
     * @return void
     */
    protected function setColumnDefinitions() : void
    {
        $this->columnDefinitions = FluentInteractor::getColumnDefinitions($this->columns);
    }

    /**
     * Set the foreign keys.
     *
     * @return void
     */
    protected function setForeignKeys() : void
    {
        $this->foreignKeys = FluentInteractor::getForeignKeys($this->columns);
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
     * Set the stub parameters.
     *
     * @param  string  $stubClass
     * @return self
     */
    protected function setStubParameters(string $stubClass) : self
    {
        switch ($stubClass) {
            case ForeignKeyInputStub::class:
                $this->setForeignKeyParameters();
                break;
            default:
                $this->setColumnDefinitionParameters();
                break;
        }

        return $this;
    }

    /**
     * Set the parameters for a foreign key column.
     *
     * @return self
     */
    protected function setColumnDefinitionParameters() : self
    {
        $this->parameters = [
            $this->columnDefinitions,
            $this->fileType,
            $this->foreignKeys,
        ];

        return $this;
    }

    /**
     * Set the parameters for a foreign key column.
     *
     * @return self
     */
    protected function setForeignKeyParameters() : self
    {
        $this->parameters = [
            $this->foreignKeys,
        ];

        return $this;
    }

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [
            $this->inputPlaceholders => $this->getStub()
        ];

        return $this;
    }

    /**
     * Add a stub.
     *
     * @param  string  $stub
     * @return void
     */
    protected function addStub(string $stub) : void
    {
        $updatedStub = App::make($stub, $this->parameters)
            ->setNameOfResource($this->nameOfResource)
            ->getUpdatedStub();

        $this->addValue($updatedStub);
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