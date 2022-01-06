<?php

namespace Cruddy\ModelRelationships;

use Cruddy\ForeignKeyDefinition;
use Cruddy\StubEditors\StubEditor;
use Cruddy\Traits\CommandTrait;

class ModelRelationship extends StubEditor
{
    use CommandTrait;

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = "\n\t";

    /**
     * The relationship string.
     *
     * @var string
     */
    protected $relationshipString = '';

    /**
     * The foreign key.
     *
     * @var \Cruddy\ForeignKeyDefinition
     */
    protected $foreignKey;

    /**
     * The return value.
     *
     * @var string
     */
    protected $returnValue = '';

    /**
     * The return replace string.
     *
     * @var string
     */
    protected $returnReplaceString = ');';

    /**
     * The name of the class.
     *
     * @var string
     */
    protected $className = '';

    /**
     * The namespace location for the models.
     *
     * @var string
     */
    protected $useStatementLocation = 'App\Models\\';

    /**
     * The placeholders for the camel-case class name.
     *
     * @var array
     */
    protected $camelClassNamePlaceholders = [
        'DummyCamelClassName',
        '{{ camelClassName }}',
        '{{camelClassName}}',
    ];

    /**
     * The type of relationship.
     *
     * @var string
     */
    protected $relationshipType = '';

    /**
     * The acceptable types of relationships.
     *
     * @var array
     */
    protected static $relationshipTypes = [
        'default',
        'hasOne',
        'hasMany',
        'belongsTo',
        'hasOneThrough',
        'hasManyThrough',
        'belongsToMany',
        'morphTo',
        'morphOne',
        'morphMany',
        'morphToMany',
        'morphedByMany',
    ];

    /**
     * The classes used within the relationship.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * The keys used within the relationship.
     *
     * @var array
     */
    protected $keys = [];

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditor\StubEditor
     */
    protected $stubEditor;

    /**
     * The constructor method.
     *
     * @param \Cruddy\ForeignKeyDefinition  $foreignKey
     * @return void
     */
    public function __construct(ForeignKeyDefinition $foreignKey)
    {
        $this->foreignKey = $foreignKey;
        $this->setType($foreignKey->relationship);
        $this->setDefaultReturnValue();
        $this->addValuesToReturnValue($this->foreignKey->classes ?? [], 'getClassString');
        $this->addValuesToReturnValue($this->foreignKey->keys ?? [], 'getKeyString');
        $this->convertRelationshipArrayToString();
        $this->replaceInStub($this->camelClassNamePlaceholders, $this->getClassBasename($this->foreignKey->on), $this->relationshipString);
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
     * Get an empty return value. 
     *
     * @return string
     */
    protected function getEmptyReturnValue() : string
    {
        return "return '';";
    }

    /**
     * Get the default return value for the relationship.
     *
     * @return string
     */
    protected function getDefaultReturnValue() : string
    {
        return '    return $this->' . $this->getRelationshipType() . '();';
    }

    /**
     * Determine if the vallue can be added to the return value.
     *
     * @param  string  $value
     * @param  string  $callback
     * @return boolean
     */
    protected function canAddToReturnValue(string $value, string $callback) : bool
    {
        return !empty($value) && in_array($callback, $this->getCallbacks());
    }

    /**
     * Get the acceptable callbacks.
     *
     * @return array
     */
    protected function getCallbacks() : array
    {
        return [
            'getClassString',
            'getKeyString',
        ];
    }

    /**
     * Get the class string.
     *
     * @param  string  $class
     * @return string
     */
    protected function getClassString(string $class) : string
    {
        $class = $this->getStudlySingular($class);

        return $this->isFirstClass() ? "$class::class" : ", $class::class";
    }

    /**
     * Get the key string.
     *
     * @param  string  $key
     * @return string
     */
    protected function getKeyString(string $key) : string
    {
        return ", '$key'";
    }

    /**
     * Get the relationship as an array.
     *
     * @return array
     */
    protected function getRelationshipArray() : array
    {
        return [
            '/**',
            ' * Get the associated {{ camelClassName }}.',
            ' */',
            'public function {{ camelClassName }}()',
            '{',
            $this->getReturnValue(),
            '}',
        ];
    }

    /**
     * Set the type of relationship.
     *
     * @param  string  $relationshipType
     * @return void
     */
    protected function setType(string $relationshipType) : void
    {
        $this->relationshipType = $relationshipType;
    }

    /**
     * Determine if the relationship type is vaalid.
     *
     * @return boolean
     */
    public static function isValidRelationshipType(string $relationshipType) : bool
    {
        return in_array($relationshipType, self::$relationshipTypes);
    }

    /**
     * Get the accepatable relationship types.
     *
     * @return array
     */
    public static function getValidRelationshipTypes() : array
    {
        return self::$relationshipTypes;
    }

    /**
     * Set the default return value for the relationship.
     *
     * @return void
     */
    protected function setDefaultReturnValue() : void
    {
        $this->returnValue = $this->getDefaultReturnValue();
    }

    /**
     * Add values to the return value.
     *
     * @param  array  $values
     * @param  string  $callback
     * @return void
     */
    protected function addValuesToReturnValue(array $values, string $callback) : void
    {
        foreach ($values as $value) {
            if ($this->canAddToReturnValue($value, $callback)) {
                $this->addToReturnValue($this->$callback($value));
            }
        }
    }

    /**
     * Add the values to the relationship stub.
     *
     * @param  string  $value = ''
     * @return void
     */
    protected function addToReturnValue(string $value = '') : void
    {
        $this->replaceInStub([$this->returnReplaceString], $value . $this->returnReplaceString, $this->returnValue);
    }

    /**
     * Determine if the class is the first class added to return value.
     *
     * @return boolean
     */
    protected function isFirstClass() : bool
    {
        return $this->returnValue === $this->getDefaultReturnValue();
    }

    /**
     * Get the relationship string.
     *
     * @return string
     */
    protected function getReturnValue() : string
    {
        return $this->returnValue;
    }

    /**
     * Get the relationship method for the model. 
     *
     * @return string
     */
    public function getModelRelationshipStub() : string
    {
        return $this->relationshipString;
    }

    /**
     * Convert the relationship array to a string.
     *
     * @return void
     */
    protected function convertRelationshipArrayToString() : void
    {
        $this->addEndOfLineFormatting($this->relationshipString);
        $relationshipArray = $this->getRelationshipArray();

        foreach ($relationshipArray as $value) {
            $this->addToRelationshipString($value);
        }
    }

    /**
     * Get the type of relationship. 
     *
     * @return string
     */
    protected function getRelationshipType() : string
    {
        return $this->relationshipType;
    }

    /**
     * Add the values to the relationship stub.
     *
     * @param  string  $value = ''
     * @return void
     */
    protected function addToRelationshipString(string $value = '') : void
    {
        $this->relationshipString .= $value;
        $this->addEndOfLineFormatting($this->relationshipString);
    }

    /**
     * Get the namespace from the foreign key.
     *
     * @return string
     */
    public function getUseStatement() : string
    {
        return 'use ' . $this->useStatementLocation . $this->getStudlySingular($this->foreignKey->on) . ";\n";
    }
}