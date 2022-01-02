<?php

namespace Cruddy\Commands;

use Cruddy\StubEditors\Inputs\StubInputsEditor;
use Cruddy\Traits\CommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\App;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The styling for the end of the Vue data.
     *
     * @var string
     */
    protected $endOfDataLine = "\n\t\t\t";

    /**
     * The styling for the end of the Vue post data.
     *
     * @var string
     */
    protected $endOfPostDataLine = "\n\t\t\t\t";

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
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\StubEditor|null
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
        $this->setStubEditor('view');
        $stub = $this->getStub();
        $name = $this->getNameInput();
        $model = $this->stubEditor->getClassBasename($name);
        $editUrl = $this->getEditUrl($name);
        $cancelUrl = '/' . $name;
        $actionRoute = $this->getActionRoute($name);
        $inputsString = $this->getInputString();
        $vueDataString = '';
        $vuePostDataString = '';
        
        if ($this->needsVueFrontend()) {
            $this->replaceVueData($this->getInputs(), $vueDataString, $vuePostDataString);
        }

        $this->replaceNamespace($stub, $name);
        $this->stubEditor->replaceInStub($this->stubEditor->inputPlaceholders, $inputsString, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->actionPlaceholders, $actionRoute, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->editUrlPlaceholders, $editUrl, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->variableCollectionPlaceholders, $this->getCamelCasePlural($name), $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->variablePlaceholders, $name, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->cancelUrlPlaceholders, $cancelUrl, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->modelPlaceholders, $model, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->vueComponentPlaceholders, $this->getStudlyComponentName($name), $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->vueDataPlaceholders, $vueDataString, $stub);
        $this->stubEditor->replaceInStub($this->stubEditor->vuePostDataPlaceholders, $vuePostDataString, $stub);
        $this->replaceClass($stub, $name);

        return $stub;
    }

    /**
     * Get the inputs as a string.
     *
     * @return string
     */
    protected function getInputString() : string
    {
        return (App::make(StubInputsEditor::class, [$this->getInputs(), 'view']))
            ->getInputString($this->getType(), $this->getResourceName());
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
     * Replace the Vue strings with the needed values.
     *
     * @param  array  $inputs
     * @param  string  &$vueDataString
     * @param  string  &$vuePostDataString
     * @return void
     */
    protected function replaceVueData(array $inputs = [], string &$vueDataString = '', string &$vuePostDataString = '') : void
    {
        foreach ($inputs as $input) {
            $vueDataString .= $this->getVueDataString($input);
            $vuePostDataString .= $this->getVuePostDataString($input);
        }
    }

    /**
     * Get the Vue post data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getVuePostDataString(ColumnDefinition $input) : string
    {
        $vuePostDataString = '';

        if ($this->getType() === 'edit') {
            $vuePostDataString .= $input['name'] . ': this.item.' . $input['name'] . ',';
        } else {
            $vuePostDataString .= $input['name'] . ': this.' . $input['name'] . ',';
        }

        $vuePostDataString .= $this->endOfPostDataLine;

        return str_replace('  ', ' ', $vuePostDataString);
    }

    /**
     * Get the Vue data needed as a string.
     *
     * @param  ColumnDefinition  $input
     * @return string
     */
    protected function getVueDataString(ColumnDefinition $input) : string
    {
        $vueDataString = $input['name'] . ': null,' . $this->endOfDataLine;

        return str_replace('  ', ' ', $vueDataString);
    }

    /**
     * Determine if the action should go to the index route location.
     *
     * @return boolean
     */
    protected function shouldSendToIndex() : bool
    {
        $type = $this->getType();

        return $type === 'create' || ($type === 'index' && $this->needsVueFrontend());
    }

    /**
     * Get the route for the action.
     *
     * @param  string  $name
     * @return string
     */
    protected function getActionRoute(string $name) : string
    {
        if ($this->shouldSendToIndex()) {
            return '/' . $name;
        }
        
        if ($this->getType() === 'edit') {
            return $this->getEditActionRoute($name);
        }

        return '';
    }

    /**
     * A test to get the action route for the edit type.
     *
     * @param  string  $name
     * @return string
     */
    public function getEditActionRoute(string $name) : string
    {
        if ($this->needsVueFrontend()) {
            return "'/$name/' + this.item.id";
        }

        return '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}';
    }

    /**
     * Get the edit URL from the name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getEditUrl(string $name) : string
    {
        if ($this->needsVueFrontend()) {
            return "'/$name/' + item.id + '/edit'";
        }

        return '/' . $name . '/{{ $' . $this->getCamelCaseSingular($name) . '->id }}/edit';
    }

    /**
     * Get the studly component name.
     *
     * @param  string  $name
     * @return string
     */
    public function getStudlyComponentName() : string
    {
        $studlyTableName = $this->getStudlySingular($this->getTableName());
        $ucFirstType = ucfirst($this->getType());

        return $studlyTableName . $ucFirstType;
    }
}
