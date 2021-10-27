<?php

namespace Cruddy\Traits\Stubs;

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
}