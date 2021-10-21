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
}