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
     * @param  string  &$stub = ''
     * @return void
     */
    public function __construct(protected string $stubEditor = 'controller', protected string &$stub = '')
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
            $this->stub
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
                return $this->makeClass(ControllerStubEditor::class);
            case 'model':
                return $this->makeClass(ModelStubEditor::class);
            case 'request':
                return $this->makeClass(RequestStubEditor::class);
            case 'view':
                return $this->makeClass(ViewStubEditor::class);
            default:
                throw new UnknownStubEditorType();
        }
    }
}