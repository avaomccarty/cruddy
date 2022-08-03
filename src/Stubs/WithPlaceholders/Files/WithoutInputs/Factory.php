<?php

namespace Cruddy\Stubs\WithPlaceholders\Files\WithoutInputs;

use Cruddy\Factory as BaseFactory;
use Cruddy\Exceptions\UnknownStubEditorType;

class Factory extends BaseFactory
{
    /**
     * The constructor method.
     *
     * @param  string  $stubEditor = 'route'
     * @param  string  $nameInput = ''
     * @return void
     */
    public function __construct(protected string $stubEditor = 'route', protected string $nameInput = '')
    {
        parent::__construct();
    }

    /**
     * Set the parameters.
     *
     * @return void
     */
    protected function setParameters() : void
    {
        $this->parameters = [
            $this->nameInput
        ];
    }

    /**
     * Get the correct Stub
     *
     * @return \Cruddy\Stub
     *
     * @throws \Cruddy\Exceptions\UnknownStubEditorType 
     */
    public function get() : Stub
    {
        switch ($this->stubEditor) {
            case 'route':
                return $this->makeClass(RouteStub::class);
            case 'vue':
                return $this->makeClass(VueStub::class);
            default:
                throw new UnknownStubEditorType();
        }
    }
}