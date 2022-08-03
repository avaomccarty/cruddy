<?php

namespace Cruddy\Stubs\WithPlaceholders\Files\WithInputs;

use Illuminate\Database\Schema\ColumnDefinition;

class ViewStub extends Stub
{
    /**
     * The stub file type.
     *
     * @var string
     */
    protected $stubFileType = 'view';

    /**
     * The acceptable Vue data placeholders within a stub.
     *
     * @var string[]
     */
    public $vueDataPlaceholders = [
        'DummyVueData',
        '{{ vueData }}',
        '{{vueData}}'
    ];

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var string[]
     */
    public $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable component name placeholders within a stub.
     *
     * @var string[]
     */
    public $componentNamePlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable Vue post data placeholders within a stub.
     *
     * @var string[]
     */
    public $vuePostDataPlaceholders = [
        'DummyVuePostData',
        '{{ vuePostData }}',
        '{{vuePostData}}'
    ];

    /**
     * The acceptable Vue component placeholders within a stub.
     *
     * @var string[]
     */
    public $vueComponentPlaceholders = [
        'DummyComponentName',
        '{{ componentName }}',
        '{{componentName}}'
    ];

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var string[]
     */
    public $modelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable Vue props placeholders within a stub.
     *
     * @var string[]
     */
    public $vuePropsPlaceholders = [
        'DummyProps',
        '{{ props }}',
        '{{props}}'
    ];

    /**
     * The acceptable edit URL placeholders within a stub.
     *
     * @var string[]
     */
    public $editUrlPlaceholders = [
        'DummyEditUrl',
        '{{ editUrl }}',
        '{{editUrl}}'
    ];

    /**
     * The acceptable action placeholders within a stub.
     *
     * @var string[]
     */
    public $actionPlaceholders = [
        'DummyAction',
        '{{ action }}',
        '{{action}}'
    ];

    /**
     * The acceptable cancel URL placeholders within a stub.
     *
     * @var string[]
     */
    public $cancelUrlPlaceholders = [
        'DummyCancelUrl',
        '{{ cancelUrl }}',
        '{{cancelUrl}}'
    ];

    /**
     * The acceptable variable placeholders within a stub.
     *
     * @var string[]
     */
    public $variablePlaceholders = [
        'DummyVariable',
        '{{ variable }}',
        '{{variable}}'
    ];

    /**
     * The acceptable collection of variable placeholders within a stub.
     *
     * @var string[]
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
     * @var string[]
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
     * @var string[]
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
     * The inputs stub editor type.
     *
     * @var string
     */
    protected $inputsStubEditorType = 'view';

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct(protected string $nameInput)
    {
        parent::__construct();

        $this->setStubLocation()
            ->setInputsStubEditor()
            ->setModel()
            ->setEditUrl()
            ->setCancelUrl()
            ->setActionRoute()
            ->setInputString();
    }

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [
            $this->inputPlaceholders => $this->inputsString,
            $this->actionPlaceholders => $this->actionRoute,
            $this->editUrlPlaceholders => $this->editUrl,
            $this->variableCollectionPlaceholders => $this->getCamelCasePlural($this->nameInput),
            $this->variableCollectionPlaceholders => '',
            $this->variablePlaceholders => $this->nameInput,
            $this->cancelUrlPlaceholders => $this->cancelUrl,
            $this->modelPlaceholders => $this->model,
            $this->vueComponentPlaceholders => $this->getStudlyComponentName($this->nameInput),
            $this->vueDataPlaceholders => $this->vueDataString,
            $this->vueDataPlaceholders => '',
            $this->vuePostDataPlaceholders => $this->vuePostDataString,
        ];

        return $this;
    }

    /**
     * Get the stub file location.
     *
     * @return string
     */
    protected function getStubLocation() : string
    {
        $frontendScaffolding = $this->getFrontendScaffoldingName();
        $stubsLocation = $this->getStubsLocation();

        return $this->resolveStubPath($stubsLocation . '/views/' . $frontendScaffolding  . '/' . $this->viewType . '.stub');
    }

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

    // /**
    //  * Update the stub file.
    //  *
    //  * @return self
    //  */
    // protected function updateStub() : self
    // {
    //     return $this->replaceInStub($this->inputPlaceholders, $this->inputsString)
    //         ->replaceInStub($this->actionPlaceholders, $this->actionRoute)
    //         ->replaceInStub($this->editUrlPlaceholders, $this->editUrl)
    //         ->replaceInStub($this->variableCollectionPlaceholders, $this->getCamelCasePlural($this->nameInput))
    //         ->replaceInStub($this->variableCollectionPlaceholders, '')
    //         ->replaceInStub($this->variablePlaceholders, $this->nameInput)
    //         ->replaceInStub($this->cancelUrlPlaceholders, $this->cancelUrl)
    //         ->replaceInStub($this->modelPlaceholders, $this->model)
    //         ->replaceInStub($this->vueComponentPlaceholders, $this->getStudlyComponentName($this->nameInput))
    //         ->replaceInStub($this->vueDataPlaceholders, $this->vueDataString)
    //         ->replaceInStub($this->vueDataPlaceholders, '')
    //         ->replaceInStub($this->vuePostDataPlaceholders, $this->vuePostDataString);
    // }

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
     * Update the Vue strings with the needed values.
     *
     * @param  array  $inputs = []
     * @return void
     */
    public function setVueData(array $inputs = []) : void
    {
        foreach ($inputs as $input) {
            $this->vueDataString .= $this->getVueDataString($input);
            $this->vuePostDataString .= $this->getVuePostDataString($input);
        }
    }
    
    /**
     * Set the model.
     *
     * @return self
     */
    protected function setModel() : self
    {
        $this->model = $this->getClassBasename($this->nameInput);

        return $this;
    }

    /**
     * Set the edit URL.
     *
     * @return self
     */
    protected function setEditUrl() : self
    {
        $this->editUrl = $this->getEditUrl($this->nameInput);

        return $this;
    }

    /**
     * Set the cancel URL.
     *
     * @return self
     */
    protected function setCancelUrl() : self
    {
        $this->cancelUrl = '/' . $this->nameInput;

        return $this;
    }

    /**
     * Set the action route.
     *
     * @return self
     */
    protected function setActionRoute() : self
    {
        $this->actionRoute = $this->getActionRoute($this->nameInput);

        return $this;
    }

    /**
     * Set the input string.
     *
     * @return self
     */
    protected function setInputString() : self
    {
        $this->inputString = $this->getInputString();

        return $this;
    }

    /**
     * Get the Vue post data needed as a string.
     *
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $input
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
     * @param  \Illuminate\Database\Schema\ColumnDefinition  $input
     * @return string
     */
    protected function getVueDataString(ColumnDefinition $input) : string
    {
        $vueDataString = $input['name'] . ': null,' . $this->endOfDataLine;

        return str_replace('  ', ' ', $vueDataString);
    }

    /**
     * Get the route for the action.
     *
     * @param  string  $name
     * @return string
     */
    protected function getActionRoute(string $name) : string
    {
        if ($this->shouldSendToIndex()) {
            return '/' . $name;
        }
        
        if ($this->getType() === 'edit') {
            return $this->getEditActionRoute($name);
        }

        return '';
    }

    /**
     * Determine if the action should go to the index route location.
     *
     * @return boolean
     */
    protected function shouldSendToIndex() : bool
    {
        $type = $this->getType();

        return $type === 'create' || ($type === 'index' && $this->needsVueFrontend());
    }


    /**
     * A test to get the action route for the edit type.
     *
     * @param  string  $name
     * @return string
     */
    public function getEditActionRoute(string $name) : string
    {
        if ($this->needsVueFrontend()) {
            return "'/$name/' + this.item.id";
        }

        return '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}';
    }

    /**
     * Get the edit URL from the name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getEditUrl(string $name) : string
    {
        if ($this->needsVueFrontend()) {
            return "'/$name/' + item.id + '/edit'";
        }

        return '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}/edit';
    }

    /**
     * Get the studly component name.
     *
     * @param  string  $name
     * @return string
     */
    public function getStudlyComponentName() : string
    {
        $studlyTableName = $this->getStudlySingular($this->getTableName());
        $ucFirstType = ucfirst($this->getType());

        return $studlyTableName . $ucFirstType;
    }

    /**
     * Get the inputs as a string.
     *
     * @return string
     */
    protected function getInputString() : string
    {
        return $this->inputsStubEditor
            ->setInputStringType($this->viewType)
            ->getInputStrings();
    }
}