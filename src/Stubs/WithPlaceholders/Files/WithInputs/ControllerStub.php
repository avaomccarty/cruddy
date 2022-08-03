<?php

namespace Cruddy\Stubs\WithPlaceholders\Files\WithInputs;

class ControllerStub extends Stub
{
    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'controller';

    /**
     * Determines if the controller is for an API.
     *
     * @var boolean
     */
    protected $isApi = false;

    /**
     * The constructor method.
     *
     * @param  array  $columns
     * @param  string  $fileType = ''
     * @param  array  $foreignKeys = []
     * @return void
     */
    public function __construct(protected array $columns, protected string $fileType = '', protected array $foreignKeys = [])
    {
        parent::__construct($columns, $fileType, $foreignKeys);

        $this->setIsApi();
    }

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [
            $this->inputPlaceholders => $this->getStubEditorInputStrings(),
            $this->resourcePlaceholders => $this->getResource(),
        ];

        return $this;
    }

    /**
     * Set the value to determine if this is for an API controller.
     *
     * @return void
     */
    protected function setIsApi() : void
    {
        $this->isApi = $this->getIsApi();
    }

    /**
     * Get the is API value.
     *
     * @return boolean
     */
    public function getIsApi() : bool
    {
        return $this->isApi;
    }

    /**
     * Get the stub file type. 
     *
     * @return string
     */
    protected function getStubFileType() : string
    {
        return $this->getIsApi() ? 'controller.api' : 'controller';
    }

    /**
     * Get the name for the resource.
     *
     * @return string
     */
    protected function getResource() : string
    {
        return str_ireplace('controller', '', $this->getNameString());
    }
}