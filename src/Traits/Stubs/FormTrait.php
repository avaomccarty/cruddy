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
}