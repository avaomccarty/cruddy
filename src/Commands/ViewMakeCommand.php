<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConsoleCommandTrait;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
    use CommandTrait, ConsoleCommandTrait;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'view';

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
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cruddy:view';

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
     * The model.
     *
     * @var string
     */
    protected $model = '';

    /**
     * The edit URL.
     *
     * @var string
     */
    protected $editUrl = '';

    /**
     * The cancel URL.
     *
     * @var string
     */
    protected $cancelUrl = '';

    /**
     * The action route.
     *
     * @var string
     */
    protected $actionRoute = '';

    /**
     * The inputs string.
     *
     * @var string
     */
    protected $inputsString = '';

    /**
     * The Vue data string.
     *
     * @var string
     */
    protected $vueDataString = '';

    /**
     * The Vue POST data string.
     *
     * @var string
     */
    protected $vuePostDataString = '';

    /**
     * Create a new view make command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setInitialVariables()
            ->setViewType()
            ->setModel()
            ->setEditUrl()
            ->setCancelUrl()
            ->setVueData();
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
        $this->stubEditor->replaceInStub($this->stubEditor->inputPlaceholders, $this->inputsString, $this->stub)
            ->replaceInStub($this->stubEditor->actionPlaceholders, $this->actionRoute, $this->stub)
            ->replaceInStub($this->stubEditor->editUrlPlaceholders, $this->editUrl, $this->stub)
            ->replaceInStub($this->stubEditor->variableCollectionPlaceholders, $this->getCamelCasePlural($this->nameInput), $this->stub)
            ->replaceInStub($this->stubEditor->variableCollectionPlaceholders, '', $this->stub)
            ->replaceInStub($this->stubEditor->variablePlaceholders, $this->nameInput, $this->stub)
            ->replaceInStub($this->stubEditor->cancelUrlPlaceholders, $this->cancelUrl, $this->stub)
            ->replaceInStub($this->stubEditor->modelPlaceholders, $this->model, $this->stub)
            ->replaceInStub($this->stubEditor->vueComponentPlaceholders, $this->getStudlyComponentName($this->nameInput), $this->stub)
            ->replaceInStub($this->stubEditor->vueDataPlaceholders, $this->vueDataString, $this->stub)
            ->replaceInStub($this->stubEditor->vueDataPlaceholders, '', $this->stub)
            ->replaceInStub($this->stubEditor->vuePostDataPlaceholders, $this->vuePostDataString, $this->stub);

        return $this->stub;
    }

    /**
     * Set the type for the stub editor.
     *
     * @return self
     */
    protected function setViewType() : self
    {
        $this->stubEditor->setViewType($this->getViewType());

        return $this;
    }

    /**
     * Get the view type.
     *
     * @return string
     */
    protected function getViewType() : string
    {
        return $this->needsVueFrontend() ? 'page' : $this->getType();
    }

    /**
     * Get the inputs as a string.
     *
     * @return string
     */
    protected function getInputString() : string
    {
        return $this->getInputsStubEditor('view')
            ->setNameOfResource($this->getResourceName())
            ->getInputStrings();
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
    protected function getArguments()
    {
        $arguments = parent::getArguments();
        $arguments[] = ['inputs', null, InputArgument::IS_ARRAY, 'The inputs for the resource'];
        $arguments[] = ['table', null, InputArgument::REQUIRED, 'The name of the table'];
        $arguments[] = ['type', null, InputArgument::REQUIRED, 'The type of file.'];

        return $arguments;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputArgument::OPTIONAL, 'Force the file to be created.']
        ];
    }

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
    //  * Set the stub.
    //  *
    //  * @return self
    //  */
    // protected function setStub() : self
    // {
    //     $this->stub = $this->getStub();

    //     return $this;
    // }

    /**
     * Set the model.
     *
     * @return self
     */
    protected function setModel() : self
    {
        $this->model = $this->stubEditor->getClassBasename($this->nameInput);

        return $this;
    }

    /**
     * Set the edit URL.
     *
     * @return self
     */
    protected function setEditUrl() : self
    {
        $this->editUrl = $this->getEditUrl($this->nameInput);

        return $this;
    }

    /**
     * Set the cancel URL.
     *
     * @return self
     */
    protected function setCancelUrl() : self
    {
        $this->cancelUrl = '/' . $this->nameInput;

        return $this;
    }

    // /**
    //  * Set the name input.
    //  *
    //  * @return self
    //  */
    // protected function setNameInput() : self
    // {
    //     $this->nameInput = $this->getNameInput();

    //     return $this;
    // }

    /**
     * Set the action route.
     *
     * @return self
     */
    protected function setActionRoute() : self
    {
        $this->actionRoute = $this->getActionRoute($this->nameInput);

        return $this;
    }

    /**
     * Set the input string.
     *
     * @return self
     */
    protected function setInputString() : self
    {
        $this->inputString = $this->getInputString();

        return $this;
    }

    /**
     * Update the Vue strings with the needed values.
     *
     * @param  array  $inputs = []
     * @return void
     */
    protected function setVueData(array $inputs = []) : void
    {
        if ($this->needsVueFrontend()) {
            foreach ($inputs as $input) {
                $this->vueDataString .= $this->getVueDataString($input);
                $this->vuePostDataString .= $this->getVuePostDataString($input);
            }
        }
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

    // /**
    //  * Replace the Vue strings with the needed values.
    //  *
    //  * @param  array  $inputs = []
    //  * @param  string  &$vueDataString = ''
    //  * @param  string  &$vuePostDataString = ''
    //  * @return void
    //  */
    // protected function replaceVueData(array $inputs = [], string &$vueDataString = '', string &$vuePostDataString = '') : void
    // {
    //     foreach ($inputs as $input) {
    //         $vueDataString .= $this->getVueDataString($input);
    //         $vuePostDataString .= $this->getVuePostDataString($input);
    //     }
    // }

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
