<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class VueViewMakeCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:vue-view
                            { name : The name of the resource that needs new Vue view files. }
                            { table : The name of the original migration table. }
                            { type=index : The type of file being created. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy Vue view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy Vue view';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\StubEditor|null
     */
    protected $stubEditor;

    /**
     * Get the stub.
     *
     * @return string
     */
    protected function getStub() : string
    {
        return App::make(StubEditor::class, ['view'])
            ->getStubFile();
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
        $stub = $this->getStub();
        $type = $this->getType();
        $variableName = $this->getVueVariableName($type);
        $componentName = $this->getComponentName($name);
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
        return $this->getLowerSingular($this->getTableName());
    }

        /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) : string
    {
        return $rootNamespace . '\resources\views';
    }

    /**
     * Get the props string for the stub.
     *
     * @return string
     */
    protected function getPropsString() : string
    {
        $table = $this->getTableName();
        $type = $this->getType();

        if ($type === 'index') {
            $prop = strtolower(trim($table));
            return ' :prop-items="{{ json_encode($' . $prop . '->toArray()[\'data\']) }}"';
        } else if ($type === 'show' || $type === 'edit') {
            $prop = $this->getLowerSingular($table);
            return ' :prop-item="{{ $' . $prop . ' }}"';
        }

        return '';
    }

    /**
     * Get the Vue variable name.
     *
     * @param  string  $type
     * @return string
     */
    public function getVueVariableName(string $type = 'index') : string
    {
        $className = $this->getClassName();
        if ($type === 'index') {
            return $this->getLowerPlural($className);
        }
        
        return strtolower($className);
    }

    /**
     * Get the component name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getComponentName(string $name) : string
    {
        $kebabName = Str::kebab($name);

        return $kebabName . '-' . $this->getType();
    }
}
