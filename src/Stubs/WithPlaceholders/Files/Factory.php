<?php

namespace Cruddy\Stubs\WithPlaceholders\Files;

use Cruddy\Factory as BaseFactory;
use Cruddy\Exceptions\UnknownStubEditorType;
use Cruddy\Stubs\Stub;
use Cruddy\Stubs\WithPlaceholders\Files\WithInputs\Factory as WithInputsFactory;
use Cruddy\Stubs\WithPlaceholders\Files\WithoutInputs\Factory as WithoutInputsFactory;

class Factory extends BaseFactory
{
    /**
     * The acceptable stubs with inputs.
     *
     * @var string[]
     */
    protected $stubsWithInputs = [
        'controller',
        'model',
        'request',
        'view',
    ];

    /**
     * The acceptable stubs without inputs.
     *
     * @var string[]
     */
    protected $stubsWithoutInputs = [
        'route',
        'vue',
    ];

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
            case in_array($this->stubEditor, $this->stubsWithInputs):
                return $this->makeClass(WithInputsFactory::class);
            case in_array($this->stubEditor, $this->stubsWithoutInputs):
                return $this->makeClass(WithoutInputsFactory::class);
            default:
                throw new UnknownStubEditorType();
        }
    }
}