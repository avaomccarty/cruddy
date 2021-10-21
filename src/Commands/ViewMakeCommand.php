<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\FormTrait;
use Cruddy\Traits\Stubs\ModelTrait;
use Cruddy\Traits\Stubs\VariableTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Cruddy\Traits\ViewMakeCommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
    use ViewMakeCommandTrait, ModelTrait, VariableTrait, CommandTrait, FormTrait, VueTrait;

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
        $stub = $this->files->get($this->getStub());
        $name = $this->getNameInput();
        $model = $this->getClassBasename($name);

        return $this->replaceNamespace($stub, $name)
            ->replaceInputs($stub)
            ->replaceFormAction($stub)
            ->replaceFormEditUrl($stub)
            ->replaceVariableCollectionPlaceholders($name, $stub)
            ->replaceVariablePlaceholders($name, $stub)
            ->replaceFormCancelUrl($stub)
            ->replaceModelPlaceholders($model, $stub)
            ->replaceVueComponentName($stub)
            ->replaceVueData($stub)
            ->replaceVuePostData($stub)
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

        if ($this->needsVueFrontend(true)) {
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
