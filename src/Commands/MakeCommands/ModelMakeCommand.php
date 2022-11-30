<?php

namespace Cruddy\Commands\MakeCommands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand as BaseModelMakeCommand;
use Symfony\Component\Console\Input\InputArgument;

class ModelMakeCommand extends BaseModelMakeCommand
{
    use CommandTrait;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'model';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:model';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\ModelStub|null
     */
    protected $stubEditor;

    /**
     * Create a new make command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setInitialVariables();
        
        $this->stubEditor->setIsPivot($this->option('pivot'))
            ->setInputs($this->getInputs());
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        $arguments = parent::getArguments();
        $arguments[] = ['inputs', null, InputArgument::IS_ARRAY, 'The inputs for the resource'];
        $arguments[] = ['keys', null, InputArgument::IS_ARRAY, 'The keys for the resource'];

        return $arguments;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return parent::getOptions();
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
        return $this->stubEditor->getUpdatedStub();
    }

    /**
     * Get the name input.
     *
     * @return string
     */
    protected function getNameInput() : string
    {
        return $this->qualifyClass($this->getStudlySingular($this->getArgument('name')));
    }

    /**
     * Get the stub.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->stubEditor->getStubFile();
    }
}
