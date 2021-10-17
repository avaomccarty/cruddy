<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;

trait FormTrait
{
    use ConfigTrait, CommandTrait;

    /**
     * The acceptable action placeholders within a stub.
     *
     * @var array
     */
    protected $stubActionPlaceholders = [
        'DummyAction',
        '{{ action }}',
        '{{action}}'
    ];
        
    /**
     * The acceptable edit URL placeholders within a stub.
     *
     * @var array
     */
    protected $stubEditUrlPlaceholders = [
        'DummyEditUrl',
        '{{ editUrl }}',
        '{{editUrl}}'
    ];

    /**
     * The acceptable cancel URL placeholders within a stub.
     *
     * @var array
     */
    protected $stubCancelUrlPlaceholders = [
        'DummyCancelUrl',
        '{{ cancelUrl }}',
        '{{cancelUrl}}'
    ];

    /**
     * Replace the action for the form request.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceFormAction(string &$stub) : self
    {
        $name = $this->argument('name');

        if ($this->getType() === 'create' || ($this->getType() === 'index' && $this->needsVueFrontend())) {
            $stub = str_replace($this->stubActionPlaceholders, '/' . $name, $stub);
        } else if ($this->getType() === 'edit') {
            if ($this->needsVueFrontend()) {
                $route = "'/$name/' + this.item.id";
                $stub = str_replace($this->stubActionPlaceholders, $route, $stub);
            } else {
                $stub = str_replace($this->stubActionPlaceholders, '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}', $stub);
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
        $name = $this->argument('name');

        if ($this->needsVueFrontend()) {
            $editUrl = "'/$name/' + item.id + '/edit'";
            $stub = str_replace($this->stubEditUrlPlaceholders, $editUrl, $stub);
        } else {
            $stub = str_replace($this->stubEditUrlPlaceholders, '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}/edit', $stub);
        }

        return $this;
    }

    /**
     * Replace the cancelUrl for the the cancel button.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceFormCancelUrl(string &$stub) : self
    {
        $stub = str_replace($this->stubCancelUrlPlaceholders, '/' . $this->argument('name'), $stub);

        return $this;
    }
}