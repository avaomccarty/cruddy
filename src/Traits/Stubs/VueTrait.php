<?php

namespace Cruddy\Traits\Stubs;

use Illuminate\Support\Str;
use Illuminate\Database\Schema\ColumnDefinition;
trait VueTrait
{
    /**
     * The acceptable vue data placeholders within a stub.
     *
     * @var array
     */
    protected $stubVueDataPlaceholders = [
        'DummyVueData',
        '{{ vueData }}',
        '{{vueData}}'
    ];

    /**
     * The acceptable vue post data placeholders within a stub.
     *
     * @var array
     */
    protected $stubVuePostDataPlaceholders = [
        'DummyVuePostData',
        '{{ vuePostData }}',
        '{{vuePostData}}'
    ];

    /**
     * The acceptable vue component placeholders within a stub.
     *
     * @var array
     */
    protected $stubVueComponentPlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable vue props placeholders within a stub.
     *
     * @var array
     */
    protected $stubVuePropsPlaceholders = [
        'DummyProps',
        '{{ props }}',
        '{{props}}'
    ];

    /**
     * The acceptable component name placeholders within a stub.
     *
     * @var array
     */
    protected $stubComponentNamePlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];
        
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
     * Replace the Vue data props variables for the given stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return string
     */
    protected function replaceVueDataPlaceholders(string $value, string &$stub) : string
    {
        return str_replace($this->stubVueDataPlaceholders, $value, $stub);
    }
        
    /**
     * Replace the Vue data props variables for the given stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return string
     */
    protected function replaceVuePostDataPlaceholders(string $value, string &$stub) : string
    {
        return str_replace($this->stubVueDataPlaceholders, $value, $stub);
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
     * Replace the Vue component name.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVueComponentName(string &$stub) : self
    {
        if ($this->needsVueFrontend()) {
            $studylyTableName = $this->getStudlySingular($this->getTableName());
            $ucFirstType = Str::ucfirst($this->getType());
            $componentName = $studylyTableName . $ucFirstType;

            $stub = str_replace($this->stubVueComponentPlaceholders, $componentName, $stub);
        }

        return $this;
    }

}