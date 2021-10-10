<?php

namespace Cruddy\Traits;

use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\UrlTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Str;

trait ViewMakeCommandTrait
{
    use CommandTrait, ModelTrait, FormTrait, UrlTrait, VueTrait;

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
     * Replace the model variable for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModelVariable(string &$stub) : self
    {
        $modelVariable = lcfirst(class_basename($this->argument('name')));

        $stub = str_replace($this->stubModelPlaceholders, $modelVariable, $stub);

        return $this;
    }

    /**
     * Replace the action for the form request.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceFormAction(string &$stub) : self
    {
        $table = $this->getTableName();

        if ($this->getType() === 'create' || ($this->getType() === 'index' && $this->needsVueFrontend())) {
            $stub = str_replace($this->stubActionPlaceholders, '/' . $table, $stub);
        } else if ($this->getType() === 'edit') {
            if ($this->needsVueFrontend()) {
                $route = "'/$table/' + this.item.id";
                $stub = str_replace($this->stubActionPlaceholders, $route, $stub);
            } else {
                $stub = str_replace($this->stubActionPlaceholders, '/' . $table . '/{{ $' . $this->getClassName() . '->id }}', $stub);
            }
        }


        return $this;
    }

    /**
     * Replace the editUrl for the the edit button.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceFormEditUrl(string &$stub) : self
    {
        $table = $this->getTableName();

        if ($this->needsVueFrontend()) {
            $editUrl = "'/$table/' + item.id + '/edit'";
            $stub = str_replace($this->stubEditUrlPlaceholders, $editUrl, $stub);
        } else {
            $stub = str_replace($this->stubEditUrlPlaceholders, '/' . $table . '/{{ $' . $this->getClassName() . '->id }}/edit', $stub);
        }

        return $this;
    }

    /**
     * Replace the Vue data values.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVueData(string &$stub) : self
    {
        if ($this->needsVueFrontend()) {
            $inputs = $this->argument('inputs');

            $vueDataString = '';

            foreach ($inputs as $input) {
                $vueDataString .= $this->getVueDataString($input);
            }

            $stub = str_replace($this->stubVueDataPlaceholders, $vueDataString, $stub);
        }

        return $this;
    }

    /**
     * Replace the Vue post data values.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVuePostData(string &$stub) : self
    {
        if ($this->needsVueFrontend()) {
            $inputs = $this->argument('inputs');

            $vuePostDataString = '';

            foreach ($inputs as $input) {
                $vuePostDataString .= $this->getVuePostDataString($input);
            }

            $stub = str_replace($this->stubVuePostDataPlaceholders, $vuePostDataString, $stub);
        }

        return $this;
    }

    /**
     * Get the Vue data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getVueDataString(ColumnDefinition $input) : string
    {
        $vueDataString = '';
        $vueDataString .= $input['name'] . ': null,';
        $vueDataString .= "\n\t\t\t";

        return str_replace('  ', ' ', $vueDataString);
    }

    /**
     * Replace the cancelUrl for the the cancel button.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceFormCancelUrl(string &$stub) : self
    {
        $table = $this->getTableName();

        $stub = str_replace($this->stubCancelUrlPlaceholders, '/' . $table, $stub);

        return $this;
    }

    /**
     * Replace the Vue component name.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVueComponentName(string &$stub) : self
    {
        if ($this->needsVueFrontend()) {
            $studylyName = Str::studly(Str::singular($this->getTableName()));
            $ucFirstName = Str::ucfirst($this->getType());
            $componentName = $studylyName . $ucFirstName;

            $stub = str_replace($this->stubVueComponentPlaceholders, $componentName, $stub);
        }

        return $this;
    }
}