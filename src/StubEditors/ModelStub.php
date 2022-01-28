<?php

namespace Cruddy\StubEditors;

use Cruddy\Exceptions\UnknownRelationshipType;
use Cruddy\ForeignKeyDefinition;
use Cruddy\ModelRelationships\ModelRelationship;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;

class ModelStub extends HasPlaceholderStub
{
    /**
     * The stub file type.
     *
     * @var string
     */
    protected $stubFileType = 'model';

    /**
     * The inputs.
     *
     * @var \Illuminate\Database\Schema\ColumnDefinition[]
     */
    protected $inputs = [];

    /**
     * Determine if the odel is for a pivot.
     *
     * @var boolean
     */
    protected $isPivot = false;

    /**
     * The inputs as a string.
     *
     * @var string
     */
    protected $inputsString = '';

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setInputsString();
    }

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $foreignKeys = $this->getKeys();

        foreach ($foreignKeys as $foreignKey) {
            $this->updateStubWithForeignKey($foreignKey);
        }

        $this->placeholderValueMap = [
            $this->inputPlaceholders => $this->inputsString,
        ];

        if (!$this->stubContains($modelRelationship)) {
            $this->placeholderValueMap[] = [
                $this->modelRelationshipPlaceholders => $modelRelationship,
            ];
        }

        if (!$this->stubContains($modelUseStatement)) {
            $this->placeholderValueMap[] = [
                $this->useStatementPlaceholders => $modelUseStatement,
            ];
        }

        return $this;
    }

    /**
     * Set if the model is for a pivot.
     *
     * @param  boolean  $isPivot = false
     * @return self
     */
    public function setIsPivot(bool $isPivot = false) : self
    {
        $this->isPivot = $isPivot;

        return $this;
    }

    /**
     * Set the inputs.
     *
     * @param  array  $inputs = []
     * @return self
     */
    public function setInputs(array $inputs = []) : self
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * Get the stub file type. 
     *
     * @return string
     */
    protected function getStubFileType() : string
    {
        return $this->isPivot ? 'model.pivot' : 'model';
    }

    // /**
    //  * Update the stub file.
    //  *
    //  * @return self
    //  */
    // protected function updateStub() : self
    // {
        // $foreignKeys = $this->getKeys();

        // foreach ($foreignKeys as $foreignKey) {
        //     $this->updateStubWithForeignKey($foreignKey);
        // }
        
        // return $this->replaceInStub($this->inputPlaceholders, $this->inputsString, $this->stub)
        //     ->replaceInStub($this->getAllPlaceholders(), '', $this->stub);
    // }

    /**
     * Set the model inputs string.
     *
     * @return self
     */
    protected function setInputsString() : self
    {
        foreach ($this->inputs as $input) {
            $this->inputsString .= $this->getInputsString($input);
        }

        return $this;
    }

    /**
     * Get the model input as a string.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $input
     * @return string
     */
    protected function getInputsString(ColumnDefinition $input) : string
    {
        return "'$input->name',\n\t\t";
    }

    /**
     * Update the stub with the foreign keys information.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return void
     */
    protected function updateStubWithForeignKey(ForeignKeyDefinition $foreignKey) : void
    {
        // Todo: Make this its own stub class that gets the total string for the foreign keys.
        // You will need to probably have a CollectionStub as well.
        // You will also need to update the setPlaceholderValueMap method within this class (i.e. ModelStub).
        $modelRelationshipClass = $this->getModelRelationship($foreignKey);
        $modelUseStatement = $this->getModelUseStatement($modelRelationshipClass);
        $modelRelationship = $this->getModelRelationshipStub($modelRelationshipClass);

        if (!$this->stubContains($modelRelationship)) {
            $this->replaceInStub($this->modelRelationshipPlaceholders, $modelRelationship);
        }

        if (!$this->stubContains($modelUseStatement)) {
            $this->replaceInStub($this->useStatementPlaceholders, $modelUseStatement);
        }
    }

    /**
     * Get the model relationship.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return \Cruddy\ModelRelationships\ModelRelationship
     *
     * @throws \Cruddy\Exceptions\UnknownRelationshipType
     */
    protected function getModelRelationship(ForeignKeyDefinition $foreignKey) : ModelRelationship
    {
        if (!ModelRelationship::isValidRelationshipType($this->getForeignKeyRelationshipType($foreignKey))) {
            throw new UnknownRelationshipType();
        }

        return App::make(ModelRelationship::class, [$foreignKey]);
    }
        
    /**
     * Get the relationship type from the foreign key.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return string
     */
    protected function getForeignKeyRelationshipType(ForeignKeyDefinition $foreignKey) : string
    {
        return $foreignKey->relationship ?? '';
    }

    /**
     * Get the model relationship.
     *
     * @param  \Cruddy\ModelRelationships\ModelRelationship  $modelRelationship
     * @return string
     */
    protected function getModelRelationshipStub(ModelRelationship $modelRelationship) : string
    {
        return $modelRelationship->getModelRelationshipStub() . $this->modelRelationshipPlaceholders[0];
    }

    /**
     * Get the model relationship.
     *
     * @param  \Cruddy\ModelRelationships\ModelRelationship  $modelRelationship
     * @return string
     */
    protected function getModelUseStatement(ModelRelationship $modelRelationship) : string
    {
        return $modelRelationship->getUseStatement() . $this->useStatementPlaceholders[0];
    }
}