<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\Stubs\VueTrait;
use Cruddy\Traits\VueViewMakeCommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class VueViewMakeCommand extends GeneratorCommand
{
    use VueViewMakeCommandTrait, VueTrait, CommandTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:vue-view
                            { name : The name of the resource that needs new vue view files. }
                            { table : The name of the original migration table. }
                            { type=index : The type of file being created. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy vue view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy vue view';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return $this->resolveStubPath($this->getStubsLocation() . '/views/vue/page.stub');
    }

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
        $type = $this->getType();
        $variableName = $this->getVueVariableName($type);
        $componentName = $this->getComponentName();
        $propsString = $this->getPropsString();

        return $this->replaceNamespace($stub, $name)
            ->replaceInStub($this->componentNamePlaceholders, $componentName, $stub)
            ->replaceInStub($this->vuePropsPlaceholders, $propsString, $stub)
            ->replaceInStub($this->valuePlaceholders, $variableName ?? '', $stub)
            ->replaceClass($stub, $name);
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
            ['name', InputArgument::OPTIONAL, 'The name of the resource'],
            ['table', InputArgument::OPTIONAL, 'The name of the table'],
        ];
    }

    /**
     * Get the class name from the table argument.
     *
     * @return string
     */
    protected function getClassName() : string
    {
        return $this->getLowerSingular($this->argument('table'));
    }
}
