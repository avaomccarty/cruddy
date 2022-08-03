<?php

namespace Cruddy\Stubs\WithPlaceholders;

use Cruddy\Factory as BaseFactory;
use Cruddy\Exceptions\UnknownStubEditorType;
use Cruddy\Stubs\Stub;
use Cruddy\Stubs\WithPlaceholders\Files\Factory as FilesFactory;
use Cruddy\Stubs\WithPlaceholders\Inputs\Factory as InputsFactory;

class Factory extends BaseFactory
{
    /**
     * The constructor method.
     *
     * @param  string  $stubEditor = 'files'
     * @param  string  $nameInput = ''
     * @return void
     */
    public function __construct(protected string $stubEditor = 'files', protected string $nameInput = '')
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
            case 'files':
                return $this->makeClass(FilesFactory::class);
            case 'inputs':
                return $this->makeClass(InputsFactory::class);
            default:
                throw new UnknownStubEditorType();
        }
    }
}