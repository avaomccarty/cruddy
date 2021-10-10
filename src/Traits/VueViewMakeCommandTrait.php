<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\ValueTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Str;

trait VueViewMakeCommandTrait {

    use CommandTrait, VueTrait, ValueTrait;

    /**
     * Replace the props variable for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceProps(string &$stub) : self
    {
        $stub = str_replace($this->stubVuePropsPlaceholders, $this->getPropsString(), $stub);

        return $this;
    }

    /**
     * Get the props string for the stub.
     *
     * @return string
     */
    protected function getPropsString() : string
    {
        if (method_exists(self::class, 'argument')) {
            $table = $this->getTableName();
            $type = $this->getType();

            if ($type === 'index') {
                $prop = strtolower(Str::studly(trim($table)));
                return ' :prop-items="{{ json_encode($' . $prop . '->toArray()[\'data\']) }}"';
            } else if ($type === 'show' || $type === 'edit') {
                $table = $this->argument('table');
                $prop = strtolower(Str::studly(Str::singular(trim($table))));
                return ' :prop-item="{{ $' . $prop . ' }}"';
            }
        }

        return '';
    }

    /**
     * Replace the variable name for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVariableName(string &$stub) : self
    {
        $type = $this->getType();

        if ($type === 'index') {
            $variableName = strtolower(Str::pluralStudly($this->getClassName()));
        } else {
            $variableName = strtolower(Str::studly($this->getClassName()));
        }

        $stub = str_replace($this->stubValuePlaceholders, $variableName ?? '', $stub);

        return $this;
    }

    /**
     * Replace the model variable for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceComponentNameVariable(string &$stub) : self
    {
        if (method_exists(self::class, 'argument')) {
            $kebabName = Str::kebab($this->argument('name'));
            $componentName = $kebabName . '-' . $this->getType();

            $stub = str_replace($this->stubComponentNamePlaceholders, $componentName, $stub);
        }

        return $this;
    }

    /**
     * Get the Vue post data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getVuePostDataString(ColumnDefinition $input) : string
    {
        $vuePostDataString = '';

        if ($this->getType() === 'edit') {
            $vuePostDataString .= $input['name'] . ': this.item.' . $input['name'] . ',';
        } else {
            $vuePostDataString .= $input['name'] . ': this.' . $input['name'] . ',';
        }

        $vuePostDataString .= "\n\t\t\t\t";
        return str_replace('  ', ' ', $vuePostDataString);
    }
}