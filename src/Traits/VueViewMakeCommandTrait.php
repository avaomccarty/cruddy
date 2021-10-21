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
            $prop = strtolower(Str::singular(trim($table)));
            return ' :prop-item="{{ $' . $prop . ' }}"';
        }

        return '';
    }
}