<?php

namespace Cruddy\StubEditors;

class StubPlaceholderInteractor extends EmptyPlaceholderStub
{
    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct(protected string $stub, array $placeholders, string $value = '')
    {
        parent::__construct();

        $this->replaceInStub($placeholders, $value);
    }

    /**
     * Get the initial stub.
     *
     * @return string
     */
    protected function getInitialStub() : string
    {
        return $this->stub;
    }

    /**
     * Replace the variables with the value within the stub.
     *
     * @param  array  $variables
     * @param  string  $value = ''
     * @return self
     */
    protected function replaceInStub(array $variables, string $value = '') : self
    {
        $this->stub = str_replace($variables, $value, $this->stub);

        return $this;
    }
}