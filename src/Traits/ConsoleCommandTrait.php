<?php

namespace Cruddy\Traits;

use Cruddy\StubEditors\StubEditor;
use Illuminate\Support\Facades\App;

trait ConsoleCommandTrait
{
    /**
     * The stub.
     *
     * @var string
     */
    protected $stub = '';

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'controller';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\StubEditor|null
     */
    protected $stubEditor;

    /**
     * The name input.
     *
     * @var string
     */
    protected $nameInput = '';

    /**
     * Get the stub.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->stubEditor->getStubFile();
    }

    /**
     * Set the stub editor.
     *
     * @return self
     */
    protected function setStubEditor() : self
    {
        $this->stubEditor = App::make(StubEditor::class, [
            $this->stubEditorType,
            $this->stub,
        ]);

        return $this;
    }

    /**
     * Set the stub.
     *
     * @return self
     */
    protected function setStub() : self
    {
        $this->stub = $this->getStub();

        return $this;
    }

    /**
     * Set the name input.
     *
     * @return self
     */
    protected function setNameInput() : self
    {
        $this->nameInput = $this->getNameInput() ?? $this->getDefaultNameInput();

        return $this;
    }

    /**
     * Get the name input.
     *
     * @return string
     */
    protected function getDefaultNameInput() : string
    {
        return parent::getNameInput();
    }

    /**
     * Set the initial variables for the class.
     *
     * @return self
     */
    protected function setInitialVariables() : self
    {
        $this->setStubEditor($this->getStubEditorType())
            ->setStub();

        $this->stub = $this->replaceClass($this->stub, $this->nameInput);
        $this->replaceNamespace($this->stub, $this->nameInput);

        return $this;
    }

    /**
     * Get the stub editor type.
     *
     * @return string
     */
    protected function getStubEditorType() : string
    {
        return $this->stubEditorType;
    }
}