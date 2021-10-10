<?php

namespace Cruddy\Traits\Stubs;

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
}