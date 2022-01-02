<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
{
    use CommandTrait;

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
     * @var \Cruddy\StubEditors\RequestStubEditor|null
     */
    protected $stubEditor;

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name) : string
    {
        $this->setStubEditor('request');
        $stub = $this->getStub();

        $this->stubEditor->replaceInStub($this->stubEditor->rulesPlaceholders, $this->getReplaceRules(), $stub);
        $this->replaceNamespace($stub, $name);
        
        return $this->replaceClass($stub, $name);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() : array
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
     * @var array
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

    /**
     * Replace the rules for the given stub.
     *
     * @param  string  &$stub
     * @return string
     */
    protected function getReplaceRules() : string
    {
        return (App::make(StubInputsEditor::class, [$this->getNonIdRules(), 'request']))
            ->getInputString($this->getType());
    }

    /**
     * Get the rules without ID columns.
     *
     * @return array
     */
    protected function getNonIdRules() : array
    {
        return array_filter($this->getRules(), function ($rule) {
            return $rule->name !== 'id';
        });
    }
}
