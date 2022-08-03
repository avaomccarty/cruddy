<?php

namespace Cruddy\Stubs\WithPlaceholders\Files\WithInputs;

use Cruddy\Factory as BaseFactory;
use Cruddy\Exceptions\UnknownStubEditorType;

class Factory extends BaseFactory
{
    /**
     * The constructor method.
     *
     * @param  string  $stubEditor = 'controller'
     * @param  string  $nameInput = ''
     * @return void
     */
    public function __construct(protected string $stubEditor = 'controller', protected string $nameInput = '')
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
            case 'controller':
                return $this->makeClass(ControllerStub::class);
            case 'model':
                return $this->makeClass(ModelStub::class);
            case 'request':
                return $this->makeClass(RequestStub::class);
            case 'view':
                return $this->makeClass(ViewStub::class);
            default:
                throw new UnknownStubEditorType();
        }
    }
}