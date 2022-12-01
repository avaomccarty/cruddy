<?php

namespace Cruddy\StubEditors;

class RequestStub extends HasPlaceholderStub
{
    /**
     * The stub file type.
     *
     * @var string
     */
    protected $stubFileType = 'request';

    /**
     * The acceptable rule placeholders within a stub.
     *
     * @var string[]
     */
    public $rulesPlaceholders = [
        'DummyRules',
        '{{ rules }}',
        '{{rules}}'
    ];

    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = "\n\t\t\t";


    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [
            $this->rulesPlaceholders => $this->getStubEditorInputStrings(),
        ];

        return $this;
    }

    /**
     * Get the input string for the controller.
     *
     * @return string
     */
    protected function getStubEditorInputStrings() : string
    {
        return $this->getInputsStubEditor($this->stubEditorType)
            ->getInputStrings();
    }
}