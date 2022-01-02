<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\File;

class ViewStubEditor extends StubEditor
{
    /**
     * The acceptable Vue data placeholders within a stub.
     *
     * @var array
     */
    public $vueDataPlaceholders = [
        'DummyVueData',
        '{{ vueData }}',
        '{{vueData}}'
    ];

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var array
     */
    public $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable component name placeholders within a stub.
     *
     * @var array
     */
    public $componentNamePlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable Vue post data placeholders within a stub.
     *
     * @var array
     */
    public $vuePostDataPlaceholders = [
        'DummyVuePostData',
        '{{ vuePostData }}',
        '{{vuePostData}}'
    ];

    /**
     * The acceptable Vue component placeholders within a stub.
     *
     * @var array
     */
    public $vueComponentPlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var array
     */
    public $modelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable Vue props placeholders within a stub.
     *
     * @var array
     */
    public $vuePropsPlaceholders = [
        'DummyProps',
        '{{ props }}',
        '{{props}}'
    ];

    /**
     * The acceptable edit URL placeholders within a stub.
     *
     * @var array
     */
    public $editUrlPlaceholders = [
        'DummyEditUrl',
        '{{ editUrl }}',
        '{{editUrl}}'
    ];

    /**
     * The acceptable action placeholders within a stub.
     *
     * @var array
     */
    public $actionPlaceholders = [
        'DummyAction',
        '{{ action }}',
        '{{action}}'
    ];

    /**
     * The acceptable cancel URL placeholders within a stub.
     *
     * @var array
     */
    public $cancelUrlPlaceholders = [
        'DummyCancelUrl',
        '{{ cancelUrl }}',
        '{{cancelUrl}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var array
     */
    public $variablePlaceholders = [
        'DummyVariable',
        '{{ variable }}',
        '{{variable}}'
    ];

    /**
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var array
     */
    public $variableCollectionPlaceholders = [
        'DummyVariableCollection',
        '{{ variableCollection }}',
        '{{variableCollection}}'
    ];

    /**
     * The type of view.
     *
     * @var string
     */
    protected $viewType = 'index';

    /**
     * The acceptable view types.
     *
     * @var array
     */
    protected $viewTypes = [
        'index',
        'create',
        'show',
        'edit',
        'page',
    ];
  
    /**
     * The variable placeholder arrays.
     *
     * @var array
     */
    protected $placeholders = [
        'vueDataPlaceholders',
        'vuePostDataPlaceholders',
        'vueComponentPlaceholders',
        'vuePropsPlaceholders',
        'componentNamePlaceholders',
        'actionPlaceholders',
        'editUrlPlaceholders',
        'cancelUrlPlaceholders',
        'variableCollectionPlaceholders',
        'variablePlaceholders',
        'modelPlaceholders',
    ];

    /**
     * Get the type argument.
     *
     * @return string
     */
    protected function getViewType() : string
    {
        $type = $this->getFrontendViewType();

        return $this->isValidType($type) ? $type : $this->getDefaultViewType();
    }

    /**
     * Get the frontend view type.
     *
     * @return string
     */
    protected function getFrontendViewType() : string
    {
        return $this->needsVueFrontend() ? 'page' : $this->getType();
    }

    /**
     * Get the default view type.
     *
     * @return string
     */
    protected function getDefaultViewType() : string
    {
        return $this->viewTypes[0];
    }

    /**
     * Determine if the type is valid.
     *
     * @param  string  $type = ''
     * @return boolean
     */
    protected function isValidType(string $type = '') : bool
    {
        return in_array($type, $this->viewTypes) && !($type !== 'page' && $this->needsVueFrontend() || $type === 'page' && !$this->needsVueFrontend());
    }

    /**
     * Set the view type.
     *
     * @param  string  $viewType = 'index'
     * @return 
     */
    public function setViewType(string $viewType = 'index')
    {
        $this->viewType = $this->isValidType($viewType) ? $viewType : $this->getDefaultViewType();
    }

    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        $frontendScaffolding = $this->getFrontendScaffoldingName();
        $stubsLocation = $this->getStubsLocation();

        $stubPath = $this->resolveStubPath($stubsLocation . '/views/' . $frontendScaffolding  . '/' . $this->viewType . '.stub');

        return File::get($stubPath);
    }
}