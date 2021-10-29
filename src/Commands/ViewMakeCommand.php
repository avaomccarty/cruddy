<?php

namespace Cruddy\Commands;

use Cruddy\Traits\ViewMakeCommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
    use ViewMakeCommandTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:view
                            { name : The name of the resource that needs new views. }
                            { table : The name of the table within the migration. }
                            { type=index : The type of file being created. }
                            { inputs?* : The inputs needed within the file. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy view';

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
        $stub = $this->getStubFile();
        $name = $this->getNameInput();
        $model = $this->getClassBasename($name);
        $editUrl = $this->getEditUrl($name);
        $cancelUrl = '/' . $name;
        $actionRoute = $this->getActionRoute($name);
        $inputsString = $this->getInputsString($this->typeNeedsSubmitInput());
        $vueDataString = '';
        $vuePostDataString = '';
        
        if ($this->needsVueFrontend()) {
            $this->replaceVueData($this->getInputs(), $vueDataString, $vuePostDataString);
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceInStub($this->inputPlaceholders, $inputsString, $stub)
            ->replaceInStub($this->actionPlaceholders, $actionRoute, $stub)
            ->replaceInStub($this->editUrlPlaceholders, $editUrl, $stub)
            ->replaceInStub($this->variableCollectionPlaceholders, $this->getCamelCasePlural($name), $stub)
            ->replaceInStub($this->variablePlaceholders, $name, $stub)
            ->replaceInStub($this->cancelUrlPlaceholders, $cancelUrl, $stub)
            ->replaceInStub($this->modelPlaceholders, $model, $stub)
            ->replaceInStub($this->vueComponentPlaceholders, $this->getStudlyComponentName($name), $stub)
            ->replaceInStub($this->vueDataPlaceholders, $vueDataString, $stub)
            ->replaceInStub($this->vuePostDataPlaceholders, $vuePostDataString, $stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the class name from the table argument.
     *
     * @return string
     */
    protected function getClassName() : string
    {
        return $this->getStudlySingular($this->getTableName()) ?? '';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name) : string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = strtolower($name);

        if ($this->needsVueFrontend()) {
            return $this->getVueFolder() . '/' . $this->getClassName() . '/' . $this->getType() . '.vue';
        }

        return str_replace('\\', '/', $name) . '/' . $this->getType() . '.blade.php';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() : array
    {
        return [
            ['type', InputArgument::OPTIONAL, 'The type of view'],
            ['inputs', InputArgument::OPTIONAL, 'The inputs for the view'],
            ['name', InputArgument::OPTIONAL, 'The name of the resource'],
            ['table', InputArgument::OPTIONAL, 'The name of the table'],
        ];
    }
}
