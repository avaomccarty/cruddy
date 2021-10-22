<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Str;

trait VariableTrait
{
    use StubTrait, ConfigTrait;

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
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var array
     */
    protected $variableCollectionPlaceholders = [
        'DummyVariableCollection',
        '{{ variableCollection }}',
        '{{variableCollection}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var array
     */
    protected $variablePlaceholders = [
        'DummyVariable',
        '{{ variable }}',
        '{{variable}}'
    ];

    /*
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
   protected $resourcePlaceholders = [
       'DummyResource',
       '{{ resource }}',
       '{{resource}}'
   ];

    /**
     * Get the studly singular version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCaseSingular(string $value) : string
    {
        return lcfirst(Str::studly(Str::singular(trim($value)))) ?? '';
    }

    /**
     * Get the studly plural version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCasePlural(string $value) : string
    {
        return lcfirst(Str::pluralStudly(trim($value))) ?? '';
    }

    /**
     * Get the Vue variable name.
     *
     * @param  string  $type
     * @return string
     */
    public function getVueVariableName(string $type = 'index') : string
    {
        // Note: This feels like it should belong somehwere else since this trait is unaware of the getClassName method.
        if ($type === 'index') {
            return strtolower(Str::pluralStudly($this->getClassName()));
        }
        
        return strtolower($this->getClassName()); 
    }

    /**
     * Get the edit URL from the name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getEditUrl(string $name) : string
    {
        if ($this->needsVueFrontend()) {
            return "'/$name/' + item.id + '/edit'";
        }

        return '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}/edit';
    }
}