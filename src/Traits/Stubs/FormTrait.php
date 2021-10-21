<?php

namespace Cruddy\Traits\Stubs;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;

trait FormTrait
{
    use ConfigTrait, CommandTrait, StubTrait;

    /**
     * The acceptable action placeholders within a stub.
     *
     * @var array
     */
    protected $actionPlaceholders = [
        'DummyAction',
        '{{ action }}',
        '{{action}}'
    ];
        
    /**
     * The acceptable edit URL placeholders within a stub.
     *
     * @var array
     */
    protected $editUrlPlaceholders = [
        'DummyEditUrl',
        '{{ editUrl }}',
        '{{editUrl}}'
    ];

    /**
     * The acceptable cancel URL placeholders within a stub.
     *
     * @var array
     */
    protected $cancelUrlPlaceholders = [
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
            $this->replaceActionPlaceholders('/' . $name, $stub);
        } else if ($this->getType() === 'edit') {
            if ($this->needsVueFrontend()) {
                $route = "'/$name/' + this.item.id";
                $this->replaceActionPlaceholders($route, $stub);
            } else {
                $this->replaceActionPlaceholders('/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}', $stub);
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
            $this->replaceEditUrlPlaceholders($editUrl, $stub);
        } else {
            $editUrl = '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}/edit';
            $this->replaceEditUrlPlaceholders($editUrl, $stub);
        }

        return $this;
    }

    /**
     * Replace the editUrlPlaceholders with the provided value within the stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return self
     */
    protected function replaceEditUrlPlaceholders(string $value, string &$stub) : self
    {
        $this->replaceInStub($this->editUrlPlaceholders, $value, $stub);

        return $this;
    }

    /**
     * Replace the actionPlaceholders with the provided value within the stub.
     *
     * @param  string  $value
     * @param  string  &$stub
     * @return self
     */
    protected function replaceActionPlaceholders(string $value, string &$stub) : self
    {
        $this->replaceInStub($this->actionPlaceholders, $value, $stub);

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
        $this->replaceInStub($this->cancelUrlPlaceholders, '/' . $this->argument('name'), $stub);

        return $this;
    }
}