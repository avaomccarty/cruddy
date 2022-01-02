<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

// class VueViewMakeCommand extends GeneratorCommand
// {
    // use CommandTrait;

    // /**
    //  * The console command name.
    //  *
    //  * @var string
    //  */
    // protected $name = 'cruddy:vue-view';

    // /**
    //  * The console command description.
    //  *
    //  * @var string
    //  */
    // protected $description = 'Create a new Cruddy Vue view';

    // /**
    //  * The type of class being generated.
    //  *
    //  * @var string
    //  */
    // protected $type = 'Cruddy Vue view';

    // /**
    //  * The stub editor.
    //  *
    //  * @var \Cruddy\StubEditors\StubEditor|null
    //  */
    // protected $stubEditor;

    // /**
    //  * Get the stub.
    //  *
    //  * @return string
    //  */
    // protected function getStub() : string
    // {
    //     return $this->stubEditor->getStubFile();
    // }

    // /**
    //  * Get the inputs as a string.
    //  *
    //  * @return string
    //  */
    // protected function getInputString() : string
    // {
    //     return $this->getStubInputsEditor('view')
    //         ->getInputString($this->getType(), $this->getResourceName());
    // }

    // /**
    //  * Build the class with the given name.
    //  *
    //  * @param  string  $name
    //  * @return string
    //  *
    //  * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
    //  */
    // protected function buildClass($name)
    // {
    //     $this->setStubEditor('view');
    //     $stub = $this->getStub();
    //     $type = $this->getType();
    //     $inputsString = $this->getInputString();
    //     $variableName = $this->getVueVariableName($type);
    //     $componentName = $this->getComponentName($name);
    //     $propsString = $this->getPropsString();

    //     $this->replaceNamespace($stub, $name);
    //     $this->stubEditor->replaceInStub($this->stubEditor->componentNamePlaceholders, $componentName, $stub);
    //     $this->stubEditor->replaceInStub($this->stubEditor->vuePropsPlaceholders, $propsString, $stub);
    //     $this->stubEditor->replaceInStub($this->stubEditor->valuePlaceholders, $variableName ?? '', $stub);
    //     $this->stubEditor->replaceInStub($this->stubEditor->inputPlaceholders, $inputsString, $stub);
    //     $stub = $this->replaceClass($stub, $name);

    //     return $stub;
    // }

    // /**
    //  * Get the destination class path.
    //  *
    //  * @param  string  $name
    //  * @return string
    //  */
    // protected function getPath($name) : string
    // {
    //     $name = Str::replaceFirst($this->rootNamespace(), '', $name);
    //     $name = strtolower($name);

    //     return str_replace('\\', '/', $name) . '/' . $this->getType() . '.blade.php';
    // }

    // /**
    //  * Get the console command arguments.
    //  *
    //  * @return array
    //  */
    // protected function getArguments()
    // {
    //     return [
    //         ['type', InputArgument::OPTIONAL, 'The type of view'],
    //         ['name', InputArgument::OPTIONAL, 'The name of the resource'],
    //         ['table', InputArgument::OPTIONAL, 'The name of the table'],
    //         ['inputs', InputArgument::OPTIONAL, 'The inputs'],
    //     ];
    // }

    // /**
    //  * Get the class name from the table argument.
    //  *
    //  * @return string
    //  */
    // protected function getClassName() : string
    // {
    //     return $this->getLowerSingular($this->getTableName());
    // }

    //     /**
    //  * Get the default namespace for the class.
    //  *
    //  * @param  string  $rootNamespace
    //  * @return string
    //  */
    // protected function getDefaultNamespace($rootNamespace) : string
    // {
    //     return $rootNamespace . '\resources\views';
    // }

    // /**
    //  * Get the props string for the stub.
    //  *
    //  * @return string
    //  */
    // protected function getPropsString() : string
    // {
    //     $table = $this->getTableName();
    //     $type = $this->getType();

    //     if ($type === 'index') {
    //         $prop = strtolower(trim($table));
    //         return ' :prop-items="{{ json_encode($' . $prop . '->toArray()[\'data\']) }}"';
    //     } else if ($type === 'show' || $type === 'edit') {
    //         $prop = $this->getLowerSingular($table);
    //         return ' :prop-item="{{ $' . $prop . ' }}"';
    //     }

    //     return '';
    // }

    // /**
    //  * Get the Vue variable name.
    //  *
    //  * @param  string  $type
    //  * @return string
    //  */
    // public function getVueVariableName(string $type = 'index') : string
    // {
    //     $className = $this->getClassName();
    //     if ($type === 'index') {
    //         return $this->getLowerPlural($className);
    //     }
        
    //     return strtolower($className);
    // }

    // /**
    //  * Get the component name.
    //  *
    //  * @param  string  $name
    //  * @return string
    //  */
    // protected function getComponentName(string $name) : string
    // {
    //     $kebabName = Str::kebab($name);

    //     return $kebabName . '-' . $this->getType();
    // }
// }
