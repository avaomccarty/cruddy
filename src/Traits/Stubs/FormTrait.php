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
        $name = $this->getResourceName();
        $route = '';

        if ($this->getType() === 'create' || ($this->getType() === 'index' && $this->needsVueFrontend())) {
            $route = '/' . $name;
        } else if ($this->getType() === 'edit') {
            if ($this->needsVueFrontend()) {
                $route = "'/$name/' + this.item.id";
            } else {
                $route = '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}';
            }
        }
        
        $this->replaceInStub($this->actionPlaceholders, $route, $stub);

        return $this;
    }

    /**
     * Get the URL for the cancel button.
     *
     * @param  string  $name
     * @return string
     */
    protected function getCancelUrl(string $name) : string
    {
        return '/' . $name;
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
}