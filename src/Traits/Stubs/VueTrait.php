<?php

namespace Cruddy\Traits\Stubs;

use Illuminate\Support\Str;
use Illuminate\Database\Schema\ColumnDefinition;
trait VueTrait
{
    use StubTrait;

    /**
     * The styling for the end of the Vue data.
     *
     * @var string
     */
    protected $endOfDataLine = "\n\t\t\t";

    /**
     * The styling for the end of the Vue post data.
     *
     * @var string
     */
    protected $endOfPostDataLine = "\n\t\t\t\t";

    /**
     * The acceptable vue data placeholders within a stub.
     *
     * @var array
     */
    protected $vueDataPlaceholders = [
        'DummyVueData',
        '{{ vueData }}',
        '{{vueData}}'
    ];

    /**
     * The acceptable vue post data placeholders within a stub.
     *
     * @var array
     */
    protected $vuePostDataPlaceholders = [
        'DummyVuePostData',
        '{{ vuePostData }}',
        '{{vuePostData}}'
    ];

    /**
     * The acceptable vue component placeholders within a stub.
     *
     * @var array
     */
    protected $vueComponentPlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable vue props placeholders within a stub.
     *
     * @var array
     */
    protected $vuePropsPlaceholders = [
        'DummyProps',
        '{{ props }}',
        '{{props}}'
    ];

    /**
     * The acceptable component name placeholders within a stub.
     *
     * @var array
     */
    protected $componentNamePlaceholders = [
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
        $this->replaceInStub($this->vuePropsPlaceholders, $this->getPropsString(), $stub);

        return $this;
    }
        
    /**
     * Replace the Vue data props variables for the given stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return self
     */
    protected function replaceStubComponentNamePlaceholders(string $value, string &$stub) : self
    {
        $this->replaceInStub($this->componentNamePlaceholders, $value, $stub);

        return $this;
    }
        
    /**
     * Replace the Vue data props variables for the given stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVueDataPlaceholders(string $value, string &$stub) : self
    {
        $this->replaceInStub($this->vueDataPlaceholders, $value, $stub);

        return $this;
    }
        
    /**
     * Replace the Vue data props variables for the given stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return self
     */
    protected function replaceVuePostDataPlaceholders(string $value, string &$stub) : self
    {
        $this->replaceInStub($this->vueDataPlaceholders, $value, $stub);

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

            $this->replaceInStub($this->vueDataPlaceholders, $vueDataString, $stub);
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

        $vuePostDataString .= $this->endOfPostDataLine;
        return str_replace('  ', ' ', $vuePostDataString);
    }

    /**
     * Get the Vue data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getVueDataString(ColumnDefinition $input) : string
    {
        $vueDataString = $input['name'] . ': null,' . $this->endOfDataLine;

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

            $this->replaceInStub($this->vuePostDataPlaceholders, $vuePostDataString, $stub);
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

            $this->replaceInStub($this->vueComponentPlaceholders, $componentName, $stub);
        }

        return $this;
    }

}