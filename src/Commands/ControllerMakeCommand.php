<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use CommandTrait;

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = "\n\t\t\t\t";

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:controller';

    /**
     * The acceptable model placeholders within a stub.
     *
     * @var array
     */
    protected $modelPlaceholders = [
        'DummyModelClass',
        '{{ model }}',
        '{{model}}'
    ];

    /**
     * The acceptable model variable placeholders within a stub.
     *
     * @var array
     */
    protected $modelVariablePlaceholders = [
        'DummyModelVariable',
        '{{ modelVariable }}',
        '{{modelVariable}}'
    ];

    /**
     * The acceptable full model class placeholders within a stub.
     *
     * @var array
     */
    protected $fullModelClassPlaceholders = [
        'DummyFullModelClass',
        '{{ namespacedModel }}',
        '{{namespacedModel}}'
    ];


    /**
     * The acceptable resource placeholders within a stub.
     *
     * @var array
     */
    protected $resourcePlaceholders = [
        'DummyResource',
        '{{ resource }}',
        '{{resource}}'
    ];

    /**
     * The variable placeholder arrays.
     *
     * @var array
     */
    protected $placeholders = [
        'modelVariablePlaceholders',
        'fullModelClassPlaceholders',
        'resourcePlaceholders',
    ];

    // /**
    //  * The console command signature.
    //  *
    //  * @var string
    //  */
    // protected $signature = 'cruddy:controller
    //                         { name : The class name for the controller. }
    //                         { --resource : Determines if this is a resource controller. }
    //                         { --model= : The name for the associated model. }
    //                         { --api : Determines if the controller should be for an API. }
    //                         { --inputs=* : The columns. }
    //                         { --commands=* : The foreign keys for the columns. }';

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
     * @var \Cruddy\StubEditors\StubEditor|null
     */
    protected $stubEditor;

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
        $this->setStubEditor('controller');
        $this->stubEditor->setIsApi($this->getApi());
        $stub = $this->getStub();

        if (!empty($this->getModel())) {
            $this->replaceModel($stub);
        }

        $this->replaceNamespace($stub, $name);
        $this->stubEditor->replaceInStub($this->resourcePlaceholders, $this->getResource(), $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->inputPlaceholders, $this->getInputString(), $stub);
        $stub = $this->replaceClass($stub, $name);

        return $stub;
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
     * Get the input string for the controller.
     *
     * @return string
     */
    protected function getInputString() : string
    {
        return $this->getStubInputsEditor('controller')
            ->getInputString($this->getTypeOption());
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
        
        $this->stubEditor->replaceInStub($this->modelPlaceholders, $modelClassName, $stub);
        $this->stubEditor->replaceInStub($this->modelVariablePlaceholders, $modelClassName, $stub);
        $this->stubEditor->replaceInStub($this->fullModelClassPlaceholders, $modelClass, $stub);

        return $this;
    }
}
