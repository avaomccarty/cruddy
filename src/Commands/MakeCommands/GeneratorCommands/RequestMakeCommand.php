<?php

namespace Cruddy\Commands\MakeCommands\GeneratorCommands;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
{
    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'request';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:request
                            { name : The name of the resource that needs new request files. }
                            { type=update : The type of request needed. }
                            { rules?* : The validation rules. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy request';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\RequestStub|null
     */
    protected $stubEditor;

    /**
     * The constructor.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setInitialVariables();
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
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['type', InputArgument::REQUIRED, 'The type of request class'],
            ['rules', InputArgument::OPTIONAL, 'The validation rules of request class'],
        ];
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

    /**
     * The acceptable types of requests.
     *
     * @var string[]
     */
    protected $types = [
        'update',
        'store'
    ];

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\Http\Requests';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput() : string
    {
        $type = $this->getType();
        $name = $this->getResourceName();

        return $this->getStudlySingular($type) . $this->getStudlySingular($name);
    }
}
