<?php

namespace Cruddy\Traits;

use Cruddy\StubEditors\Inputs\InputsStubInteractor;
use Cruddy\StubEditors\StubEditor;
use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait CommandTrait
{
    use ConfigTrait, PlaceholdersTrait;

    /**
     * Get an argument from the command.
     *
     * @param  string  $argument
     * @return mixed
     */
    protected function getArgument(string $argument) : mixed
    {
        if (method_exists(self::class, 'argument') && !empty($argument)) {
            return $this->argument($argument);
        }

        return null;
    }

    /**
     * Get an option from the command.
     *
     * @param  string  $option
     * @return mixed
     */
    protected function getOption(string $option) : mixed
    {
        if (method_exists(self::class, 'option')) {
            return $this->option($option);
        }

        return null;
    }

    /**
     * Get the type.
     *
     * @return string
     */
    protected function getType() : string
    {
        return (string)$this->getArgument('type') ?? '';
    }

    /**
     * Get the type option.
     *
     * @return string
     */
    protected function getTypeOption() : string
    {
        return (string)$this->getOption('type') ?? '';
    }

    /**
     * Get the table.
     *
     * @return string
     */
    protected function getTableName() : string
    {
        return (string)$this->getArgument('table') ?? '';
    }

    /**
     * Get the name.
     *
     * @return string
     */
    protected function getResourceName() : string
    {
        return (string)$this->getArgument('name') ?? '';
    }

    /**
     * Get the lower singular version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getLowerSingular(string $value) : string
    {
        return strtolower($this->getStudlySingular($value)) ?? '';
    }

    /**
     * Get the lower plural version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getLowerPlural(string $value) : string
    {
        return strtolower(Str::pluralStudly($value)) ?? '';
    }

    /**
     * Get the studly singular version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getStudlySingular(string $value) : string
    {
        return Str::studly(Str::singular(trim($value))) ?? '';
    }

    /**
     * Get the studly singular version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCaseSingular(string $value) : string
    {
        return lcfirst(Str::studly(Str::singular(trim($value)))) ?? '';
    }

    /**
     * Get the studly plural version of the string with the first character lower-case.
     *
     * @param  string  $value
     * @return string
     */
    protected function getCamelCasePlural(string $value) : string
    {
        return lcfirst(Str::pluralStudly(trim($value))) ?? '';
    }

    /**
     * Get the stub file.
     *
     * @return string
     */
    protected function getStubFile() : string
    {
        return $this->files->get($this->getStub());
    }

    /**
     * Get the name string.
     *
     * @return string
     */
    protected function getNameString() : string
    {
        return (string)$this->getArgument('name') ?? '';
    }

    /**
     * Get the inputs.
     *
     * @return array
     */
    protected function getInputs() : array
    {
        return (array)$this->getArgument('inputs') ?? [];
    }

    /**
     * Get the inputs from the options.
     *
     * @return array
     */
    protected function getInputsOption() : array
    {
        return (array)$this->getOption('inputs') ?? [];
    }

    /**
     * Get the commands.
     *
     * @return array
     */
    protected function getCommands() : array
    {
        return (array)$this->getOption('commands') ?? [];
    }

    /**
     * Get the keys.
     *
     * @return array
     */
    protected function getKeys() : array
    {
        return (array)$this->getArgument('keys') ?? [];
    }

    /**
     * Get the rules.
     *
     * @return array
     */
    public function getRules() : array
    {
        return (array)$this->getArgument('rules') ?? [];
    }

    /**
     * Determine if the resource should be an API.
     *
     * @return boolean
     */
    public function getApi() : bool
    {
        return (bool)$this->option('api') ?? false;
    }

    /**
     * Get the model.
     *
     * @return string
     */
    protected function getModel() : string
    {
        return (string)$this->option('model') ?? '';
    }


    // Note: not sure if this is needed, but it conflicts with another method in this file (11/30)

    // /**
    //  * Set the stub editor.
    //  *
    //  * @param  string  $type = 'controller'
    //  * @return self
    //  */
    // protected function setStubEditor(string $type = 'controller') : self
    // {
    //     $this->stubEditor = App::make(StubEditor::class, [$type]);

    //     return $this;
    // }


    /**
     * Set the inputs stub editor.
     *
     * @return self
     */
    protected function setInputsStubEditor() : self
    {
        $this->inputsStubEditor = $this->getInputsStubEditor($this->inputsStubEditorType, $this->getResourceName());

        return $this;
    }

    /**
     * Set the stub editor.
     *
     * @param  string  $type = 'controller'
     * @param  string  $nameOfResource = ''
     * @return \Cruddy\StubEditors\Inputs\InputsStubInteractor
     */
    protected function getInputsStubEditor(string $type = 'controller', string $nameOfResource = '') : InputsStubInteractor
    {
        $inputsStubInteractor = App::make(InputsStubInteractor::class, [
            $this->getInputsForType($type),
            $type,
        ]);

        if (!empty($nameOfResource)) {
            $inputsStubInteractor->setNameOfResource($nameOfResource);
        }

        return $inputsStubInteractor;
    }

    /**
     * Get the inputs for the file type.
     *
     * @param  string  $type
     * @return array
     */
    protected function getInputsForType(string $type) : array
    {
        switch ($type) {
            case 'controller':
                return $this->getInputsOption();
            case 'request':
                return $this->getNonIdRules();
            default:
                return $this->getInputs();
        }
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

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'controller';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\StubEditor|null
     */
    protected $stubEditor;

    /**
     * The name input.
     *
     * @var string
     */
    protected $nameInput = '';

    /**
     * Set the stub editor.
     *
     * @return self
     */
    protected function setStubEditor() : self
    {
        $this->stubEditor = App::make(StubEditor::class, [
            $this->getStubEditorType(),
            $this->nameInput,
        ]);

        return $this;
    }

    /**
     * Set the stub.
     *
     * @return self
     */
    protected function setStub() : self
    {
        $this->stub = $this->getStub();

        return $this;
    }

    /**
     * Set the name input.
     *
     * @return self
     */
    protected function setNameInput() : self
    {
        $this->nameInput = $this->getNameInput() ?? $this->getDefaultNameInput();

        return $this;
    }

    /**
     * Get the name input.
     *
     * @return string
     */
    protected function getDefaultNameInput() : string
    {
        return parent::getNameInput();
    }

    /**
     * Set the initial variables for the class.
     *
     * @return self
     */
    protected function setInitialVariables() : self
    {
        $this->setNameInput()
            ->setStubEditor();

        // $this->stub = $this->replaceClass($this->stub, $this->nameInput);
        // $this->replaceNamespace($this->stub, $this->nameInput);

        return $this;
    }

    /**
     * Get the stub editor type.
     *
     * @return string
     */
    protected function getStubEditorType() : string
    {
        return $this->stubEditorType;
    }
}