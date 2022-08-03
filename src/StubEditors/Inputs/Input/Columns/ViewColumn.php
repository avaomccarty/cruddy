<?php

namespace Cruddy\StubEditors\Inputs\Input\Columns;

use Cruddy\StubEditors\Inputs\Input\Columns\Input\AttributeInteractor;
use Illuminate\Support\Facades\App;

class ViewColumn extends ColumnStub
{
    /**
     * The acceptable value placeholders within a stub.
     *
     * @var string[]
     */
    protected $vModelNamePlaceholders = [
        'DummyVModelName',
        '{{ vModelName }}',
        '{{vModelName}}' 
    ];

    /**
     * The acceptable value placeholders within a stub.
     *
     * @var string[]
     */
    protected $valuePlaceholders = [
        'DummyValue',
        '{{ value }}',
        '{{value}}'
    ];

    /**
     * The acceptable name placeholders within a stub.
     *
     * @var string[]
     */
    protected $namePlaceholders = [
        'DummyName',
        '{{ name }}',
        '{{name}}'
    ];

    /**
     * The acceptable data placeholders within a stub.
     *
     * @var string[]
     */
    protected $dataPlaceholders = [
        'DummyData',
        '{{ data }}',
        '{{data}}'
    ];

    /**
     * The acceptable model name placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelNamePlaceholders = [
        'DummyModelName',
        '{{ ModelName }}',
        '{{ModelName}}'
    ];

    /**
     * The type for the file.
     *
     * @var string
     */
    protected $type = 'index';

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
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [
            $this->modelNamePlaceholders => $this->getReplaceString(),
            $this->namePlaceholders => $this->column['name'],
            $this->valuePlaceholders => $this->getInputValue(),
            $this->vModelNamePlaceholders => $this->column['name'],
            $this->dataPlaceholders => $this->getAttributes(),
        ];

        return $this;
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return '';
    }

    /**
     * Get the input value.
     *
     * @return string
     */
    protected function getInputValue() : string
    {
        return $this->column->type === 'submit' ? $this->column->type : '';
    }

    /**
     * Get the attributes for the input.
     *
     * @return string
     */
    protected function getAttributes() : string
    {
        return App::make(AttributeInteractor::class, [
            $this->column,
            $this->type,
        ])
            ->getUpdatedStub();
    }

    /**
     * Set the type.
     *
     * @param  string  $type
     * @return void
     */
    public function setType(string $type) : void
    {
        $this->type = $this->isValidType($type) ? $type : $this->type;
    }

    /**
     * Get the replace string for an input.
     *
     * @return string
     */
    protected function getReplaceString() : string
    {
        if ($this->isVueEditOrShow()) {
            return 'item.' . $this->column['name'];
        }
        
        return $this->column['name'];
    }

    /**
     * Determine if it is a Vue edit/show file type.
     *
     * @return bool
     */
    protected function isVueEditOrShow() : bool
    {
        return $this->needsVueFrontend() && in_array($this->type, [
            'edit',
            'show',
        ]);
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
}