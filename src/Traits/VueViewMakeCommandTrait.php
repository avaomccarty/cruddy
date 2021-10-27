<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\VariableTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Illuminate\Support\Str;

trait VueViewMakeCommandTrait {

    use CommandTrait, VueTrait, VariableTrait;

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\resources\views';
    }

    /**
     * Get the props string for the stub.
     *
     * @return string
     */
    protected function getPropsString() : string
    {
        $table = $this->getTableName();
        $type = $this->getType();

        if ($type === 'index') {
            $prop = strtolower(trim($table));
            return ' :prop-items="{{ json_encode($' . $prop . '->toArray()[\'data\']) }}"';
        } else if ($type === 'show' || $type === 'edit') {
            $prop = $this->getLowerSingular($table);
            return ' :prop-item="{{ $' . $prop . ' }}"';
        }

        return '';
    }

    /**
     * Get the Vue variable name.
     *
     * @param  string  $type
     * @return string
     */
    public function getVueVariableName(string $type = 'index') : string
    {
        $className = $this->getClassName();
        if ($type === 'index') {
            return $this->getLowerPlural($className);
        }
        
        return strtolower($className);
    }

    /**
     * Get the component name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getComponentName(string $name) : string
    {
        $kebabName = Str::kebab($name);

        return $kebabName . '-' . $this->getType();
    }
}