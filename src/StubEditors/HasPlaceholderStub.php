<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

abstract class HasPlaceholderStub extends Stub
{
    /**
     * The acceptable input placeholders within a stub.
     *
     * @var string[]
     */
    protected $inputPlaceholders = [
        'DummyInputs',
        '{{ inputs }}',
        '{{inputs}}'
    ];

    /**
     * The placeholders used within the stub.
     *
     * @var string[]
     */
    protected $placeholders = [
        'inputPlaceholders'
    ];

    /**
     * All placeholders for the stub editor.
     *
     * @var string[]
     */
    protected $allPlaceholders = [];

    /**
     * The map for the placeholders to be replaced and the corresponding value. Format: [ (string[])placeholders => (string)value ].
     *
     * @var array
     */
    protected $placeholderValueMap = [];
        
    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    abstract protected function setPlaceholderValueMap() : self;

    /**
     * Get the inputs value.
     *
     * @return string
     */
    protected function getInputsValue() : string
    {
        // Todo: Not sure why the inputPlaceholders are on this class.  
        // Given that the inputPlaceholders are on this class, there should be a value that replaces those placeholders within the stub.
        // Figure out what that value should be (the value probably comes from an InputStub or something like that)
        return '';
    }

    /**
     * Get the stub file location.
     *
     * @return string
     */
    protected function getStubLocation() : string
    {
        return $this->resolveStubPath($this->getStubsLocation() . '/' . $this->getStubFileType() . '.stub');
    }

    /**
     * Determine if stub should be empty.
     *
     * @return boolean
     */
    protected function shouldHaveStub() : bool
    {
        return true;
    }

    /**
     * Get the stub.
     *
     * @return self
     */
    protected function setStub() : self
    {
        $this->addValue($this->getInitialStub());

        return $this;
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return File::get($this->getStubLocation());
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $path
     * @return string
     */
    protected function resolveStubPath(string $path) : string
    {
        return File::exists($customPath = base_path(trim($path, '/')))
            ? $customPath
            : dirname(__DIR__) . '/Commands/' . $path;
    }

    /**
     * Get the updated stub.
     *
     * @return string
     */
    public function getStub() : string
    {
        return $this->replacePlaceholders()
            ->replaceAllPlaceholders()
            ->stub;
    }

    /**
     * Replace the placeholders within the stub.
     *
     * @return self
     */
    protected function replacePlaceholders() : self
    {
        foreach ($this->placeholderValueMap as $placeholders => $value) {
            $this->replacePlaceholder($placeholders, $value);
        }

        return $this;
    }

    /**
     * Replace the placeholders within the stub.
     *
     * @return self
     */
    protected function replaceAllPlaceholders() : self
    {
        return $this->replacePlaceholder($this->allPlaceholders);
    }

    /**
     * Get the with the placeholders replaced with the value.
     *
     * @param  array|string  $placeholder,
     * @param  string  $value = '',
     * @return self
     */
    protected function replacePlaceholder(array|string $placeholder, string $value = '') : self
    {
        $this->stub = App::make(StubPlaceholderInteractor::class, [
            $this->stub,
            $placeholder,
            $value
        ])->getStub();

        return $this;
    }

    /**
     * Set all the placeholders for this stub.
     *
     * @return self
     */
    protected function setAllPlaceholders() : self
    {
        foreach ($this->placeholders as $placeholders) {
            array_push($this->allPlaceholders, ...$this->$placeholders);
        }

        return $this;
    }
}