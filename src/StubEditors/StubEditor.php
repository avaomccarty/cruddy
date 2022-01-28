<?php

namespace Cruddy\StubEditors;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\File;

abstract class StubEditor
{
    use ConfigTrait, CommandTrait;

    // /**
    //  * The stub to be edited.
    //  *
    //  * @var string
    //  */
    // protected $stub = '';

    // /**
    //  * The stub location.
    //  *
    //  * @var string
    //  */
    // protected $stubLocation = '';

    // /**
    //  * The string needed between values.
    //  *
    //  * @var string
    //  */
    // protected $spacer = '';

    // /**
    //  * The default end of line formatting.
    //  *
    //  * @var string
    //  */
    // protected $defaultSpacer = "\n\t\t";

    // /**
    //  * The acceptable input placeholders within a stub.
    //  *
    //  * @var array
    //  */
    // public $inputPlaceholders = [
    //     'DummyInputs',
    //     '{{ inputs }}',
    //     '{{inputs}}'
    // ];

    // /**
    //  * The placeholders used within the stub.
    //  *
    //  * @var array
    //  */
    // protected $placeholders = [
    //     'inputPlaceholders'
    // ];

    // /**
    //  * All placeholders for the stub editor.
    //  *
    //  * @var array
    //  */
    // protected $allPlaceholders = [];

    // /**
    //  * The constructor method.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->stub = $this->getStubFile();
    //     $this->setAllPlaceholders();
    // }

    // /**
    //  * The destructor method.
    //  *
    //  * @return void
    //  */
    // public function __destruct()
    // {
    //     $this->replaceInStub($this->allPlaceholders, '');
    //     $this->removeExtraSpacer($this->stub);
    // }

    // /**
    //  * Set all the placeholders for this stub.
    //  *
    //  * @return self
    //  */
    // protected function setAllPlaceholders() : self
    // {
    //     foreach ($this->placeholders as $placeholders) {
    //         array_push($this->allPlaceholders, ...$placeholders);
    //     }

    //     return $this;
    // }

    // /**
    //  * Get the stub.
    //  *
    //  * @return string
    //  */
    // protected function getStubFile() : string
    // {
    //     return File::get($this->getStubLocation());
    // }

    // /**
    //  * Get the stub file type. 
    //  *
    //  * @return string
    //  */
    // protected function getStubFileType() : string
    // {
    //     return $this->stubFileType;
    // }

    // /**
    //  * Set the stub location.
    //  *
    //  * @return self
    //  */
    // protected function setStubLocation() : self
    // {
    //     $this->stubLocation = $this->getStubLocation();
    
    //     return $this;
    // }

    // /**
    //  * Get the stub file location.
    //  *
    //  * @return string
    //  */
    // protected function getStubLocation() : string
    // {
    //     return $this->resolveStubPath($this->getStubsLocation() . '/' . $this->getStubFileType() . '.stub');
    // }

    // /**
    //  * Get the stub file location.
    //  *
    //  * @return string
    //  */
    // protected function getStub() : string
    // {
    //     return $this->stubLocation;
    // }

    // /**
    //  * Get the updated stub.
    //  *
    //  * @return string
    //  */
    // public function getUpdatedStub() : string
    // {
    //     // return $this->updateStub()->stub;

    //     return $this->prepStub()
    //         ->updateStub()
    //         ->addStubEnding()
    //         ->stub;
    // }

    // /**
    //  * Get the default types.
    //  *
    //  * @return array
    //  */
    // protected function getDefaultTypes() : array
    // {
    //     return [
    //         'index',
    //         'create',
    //         'show',
    //         'edit',
    //      ];
    // }

    // /**
    //  * Determine if the type is valid.
    //  *
    //  * @param  string  $type = ''
    //  * @return boolean
    //  */
    // protected function isValidType(string $type = '') : bool
    // {
    //     return in_array($type, $this->getDefaultTypes());
    // }

    // /**
    //  * Replace the variables with the correct value within the stub.
    //  *
    //  * @param  array  $variables
    //  * @param  string  $value = ''
    //  * @param  string  ?&$stub = null
    //  * @return self
    //  */
    // public function replaceInStub(array $variables, string $value = '', ?string &$stub = null) : self
    // {
    //     if (is_null($stub)) {
    //         $this->replaceInStubProperty($variables, $value);
    //     } else {
    //         $this->replaceInProvidedStub($variables, $value, $stub);
    //     }


    //     return $this;
    // }

    // /**
    //  * Replace the variables with the correct value within the stub property.
    //  *
    //  * @param  array  $variables
    //  * @param  string  $value
    //  * @return 
    //  */
    // protected function replaceInStubProperty(array $variables, string $value) : void
    // {
    //     $this->stub = str_replace($variables, $value, $this->stub);
    // }

    // /**
    //  * Replace the variables with the correct value within the provided stub.
    //  *
    //  * @param  array  $variables
    //  * @param  string  $value
    //  * @param  string  &$stub
    //  * @return 
    //  */
    // protected function replaceInProvidedStub(array $variables, string $value, string &$stub) : void
    // {
    //     $stub = str_replace($variables, $value, $stub);
    // }

    // /**
    //  * Get the camelcase version of the class from the namespace.
    //  *
    //  * @param  string  $value
    //  * @return string
    //  */
    // public function getClassBasename(string $value) : string
    // {
    //     return lcfirst(class_basename($value)) ?? '';
    // }

    // /**
    //  * Determine if the stub ends in a spacer.
    //  *
    //  * @return boolean
    //  */
    // protected function endsInSpacer() : bool
    // {
    //     return substr($this->stub, -strlen($this->getSpacer())) === $this->getSpacer();
    // }


    // /**
    //  * Determine if a spacer is needed.
    //  *
    //  * @return boolean
    //  */
    // protected function needsSpacer() : bool
    // {
    //     return !empty($this->stub) && !$this->endsInSpacer();
    // }

    // /**
    //  * Add a spacer within the stub.
    //  *
    //  * @return void
    //  */
    // protected function addSpacer() : void
    // {
    //     $this->stub .= $this->spacer;
    // }

    // /**
    //  * Add value to the stub.
    //  *
    //  * @param  string  $value
    //  * @return void
    //  */
    // protected function addValue(string $value) : void
    // {
    //     if ($this->needsSpacer()) {
    //         $this->addSpacer();
    //     }

    //     $this->stub .= $value;
    // }

    // /**
    //  * Get the spacer.
    //  *
    //  * @return string
    //  */
    // protected function getSpacer() : string
    // {
    //     return empty($this->spacer) ? $this->defaultSpacer : $this->spacer;
    // }

    // /**
    //  * Remove the last spacer.
    //  *
    //  * @param  string  &$value
    //  * @return void
    //  */
    // public function removeExtraSpacer(string &$value) : void
    // {
    //     if ($this->endsInSpacer($value)) {
    //         $value = substr($value, 0, -strlen($this->getSpacer()));
    //     }
    // }

    // /**
    //  * Resolve the fully-qualified path to the stub.
    //  *
    //  * @param  string  $path
    //  * @return string
    //  */
    // protected function resolveStubPath(string $path) : string
    // {
    //     return File::exists($customPath = base_path(trim($path, '/')))
    //         ? $customPath
    //         : dirname(__DIR__) . '/Commands/' . $path;
    // }

    // /**
    //  * Check if the stub already contains the provided string.
    //  *
    //  * @param  string  $needle
    //  * @return boolean
    //  */
    // public function stubContains(string $needle) : bool
    // {
    //     return str_contains($this->stub, $needle);
    // }

    // /**
    //  * Get the input string for the controller.
    //  *
    //  * @return string
    //  */
    // protected function getStubEditorInputStrings() : string
    // {
    //     return $this->getInputsStubEditor($this->stubEditorType)
    //         ->getInputStrings();
    // }
}