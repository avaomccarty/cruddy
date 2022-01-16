<?php

namespace Cruddy\StubEditors\Inputs\Input\ForeignKeys;

use Cruddy\Exceptions\UnknownRelationshipType;
use Cruddy\Factory;
use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\Inputs\Input\InputStubEditor;
use Cruddy\ModelRelationships\ModelRelationship;

class ForeignKeyInputStubEditorFactory extends Factory
{
    /**
     * The relatioship for the foreign key.
     *
     * @var string|null
     */
    protected $relationship;

    /**
     * The name of the foreign key input class.
     *
     * @var string
     */
    protected $className = '';

    /**
     * The constructor method.
     *
     * @param  \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return void
     */
    public function __construct(protected ForeignKeyDefinition $foreignKey)
    {
        parent::__construct();

        $this->relationship = $this->getRelationship();
        $this->className = $this->getClassName();
    }

    /**
     * Set the parameters.
     *
     * @return void
     */
    protected function setParameters() : void
    {
        $this->parameters = [
            $this->foreignKey
        ];
    }

    /**
     * Get the correct foreign key input.
     *
     * @return \Cruddy\StubEditors\Inputs\Input\InputStubEditor
     *
     * @throws \Cruddy\Exceptions\UnknownRelationshipType
     */
    public function get() : InputStubEditor
    {
        if ($this->isValidRelationship()) {
            return $this->makeClass($this->className);
        }

        throw new UnknownRelationshipType();
    }

    /**
     * Determine if the relationship has a valid foreign key input class associated. 
     *
     * @return boolean
     */
    protected function isValidRelationship() : bool
    {
        return ModelRelationship::isValidRelationshipType($this->relationship) && class_exists($this->className);
    }

    /**
     * Get the class name.
     *
     * @return string
     */
    protected function getClassName() : string
    {
        return 'Cruddy\\StubEditors\\Inputs\\Input\\ForeignKeys\\' . ucfirst($this->relationship) . 'Input';
    }

    /**
     * Get the relationship from the foreign key. 
     *
     * @return string
     */
    protected function getRelationship() : string
    {
        return $this->foreignKey->relationship;
    }
}