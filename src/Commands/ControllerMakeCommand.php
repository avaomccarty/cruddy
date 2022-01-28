<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConsoleCommandTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use CommandTrait, ConsoleCommandTrait;

    /**
     * The string needed between values.
     *
     * @var string
     */
    protected $spacer = "\n\t\t\t\t";

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:controller';

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable model variable placeholders within a stub.
     *
     * @var string[]
     */
    protected $modelVariablePlaceholders = [
        'DummyModelVariable',
        '{{ modelVariable }}',
        '{{modelVariable}}'
    ];

    /**
     * The acceptable full model class placeholders within a stub.
     *
     * @var string[]
     */
    protected $fullModelClassPlaceholders = [
        'DummyFullModelClass',
        '{{ namespacedModel }}',
        '{{namespacedModel}}'
    ];


    /**
     * The acceptable resource placeholders within a stub.
     *
     * @var string[]
     */
    protected $resourcePlaceholders = [
        'DummyResource',
        '{{ resource }}',
        '{{resource}}'
    ];

    /**
     * The variable placeholder arrays.
     *
     * @var string[]
     */
    protected $placeholders = [
        'modelVariablePlaceholders',
        'fullModelClassPlaceholders',
        'resourcePlaceholders',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy controller';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\ControllerStub|null
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
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = ['inputs', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The inputs for the resource', []];
        $options[] = ['commands', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The keys for the resource', []];

        return $options;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\Http\Controllers';
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
        $this->stubEditor->setIsApi($this->getApi());

        $stub = $this->stubEditor->getUpdatedStub();

        if (!empty($this->getModel())) {
            $this->replaceModel($stub);
        }

        return $stub;
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  &$stub
     * @return self
     */
    protected function replaceModel(string &$stub) : self
    {
        $modelClass = $this->parseModel($this->getModel());

        if (! class_exists($modelClass)) {
            $this->call('cruddy:model', [
                'name' => $modelClass,
                'inputs' => $this->getInputsOption(),
                'keys' => $this->getCommands(),
                '--force' => true
            ]);
        }

        $modelClassName = $this->stubEditor->getClassBasename($modelClass);
        
        $this->stubEditor->replaceInStub($this->modelPlaceholders, $modelClassName, $stub)
            ->replaceInStub($this->modelVariablePlaceholders, $modelClassName, $stub)
            ->replaceInStub($this->fullModelClassPlaceholders, $modelClass, $stub);

        return $this;
    }
}
