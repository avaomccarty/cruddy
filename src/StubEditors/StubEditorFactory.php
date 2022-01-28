<?php

namespace Cruddy\StubEditors;

use Cruddy\Exceptions\UnknownStubEditorType;
use Cruddy\Factory;
use Cruddy\StubEditors\StubEditor;

class StubEditorFactory extends Factory
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
     * Get the correct StubEditor
     *
     * @return \Cruddy\StubEditors\StubEditor
     *
     * @throws \Cruddy\Exceptions\UnknownStubEditorType 
     */
    public function get() : StubEditor
    {
        switch ($this->stubEditor) {
            case 'controller':
                return $this->makeClass(ControllerStub::class);
            case 'model':
                return $this->makeClass(ModelStub::class);
            case 'request':
                return $this->makeClass(RequestStub::class);
            case 'route':
                return $this->makeClass(RouteStubEditor::class);
            case 'vue':
                return $this->makeClass(VueStubEditor::class);
            case 'view':
                return $this->makeClass(ViewStub::class);
            default:
                throw new UnknownStubEditorType();
        }
    }
}