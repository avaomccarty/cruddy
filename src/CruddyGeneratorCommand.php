<?php

namespace Cruddy;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;

class CruddyGeneratorCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = '';

    /**
     * Create a new view make command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setStubEditor($this->stubEditorType);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->stubEditor->getStub();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->stubEditor->getUpdatedStub();

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
}