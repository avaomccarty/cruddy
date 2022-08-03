<?php

namespace Cruddy\Stubs\WithPlaceholders\Files\WithoutInputs;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VueStub extends Stub
{
    /**
     * Determine if there is a stub file.
     *
     * @var string
     */
    protected $hasStubFile = false;

    /**
     * The import statement.
     *
     * @var string
     */
    protected $importStatement = '';

    /**
     * The component statement.
     *
     * @var string
     */
    protected $componentStatement = '';

    /**
     * The component search.
     *
     * @var string
     */
    protected $componentSearch = '';

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setStubWithPlaceholdersFile()
            ->setStubLocation()
            ->setImportStatement()
            ->setComponentStatement();
    }

    /**
     * Set the placeholder value map.
     *
     * @return self
     */
    protected function setPlaceholderValueMap() : self
    {
        $this->placeholderValueMap = [];

        return $this;
    }

    /**
     * Set the import file.
     *
     * @return self
     */
    protected function setImportFile() : self
    {
        $this->stub = $this->getStubFile();
    
        return $this;
    }

    /**
     * Set the import statement.
     *
     * @return self
     */
    protected function setImportStatement() : self
    {
        $this->importStatement = $this->getImportStatement();
    
        return $this;
    }

    /**
     * Set the component statement.
     *
     * @return self
     */
    protected function setComponentStatement() : self
    {
        $this->componentStatement = $this->getComponentStatement();
    
        return $this;
    }

    /**
     * Determine if the file already has a component statment.
     *
     * @return boolean
     */
    protected function hasComponentStatement() : bool
    {
        return strpos($this->stub, $this->componentStatement) === false;
    }

    /**
     * Determine if the file already has a import statment.
     *
     * @return boolean
     */
    protected function hasImportStatement() : bool
    {
        return strpos($this->stub, $this->importStatement) === false;
    }

    /**
     * Get new Cruddy Vue component name.
     *
     * @param  string|null  $style = null
     * @return string
     */
    protected function getComponent(string $style = null) : string
    {
        $name = $this->getResourceName();
        $type = $this->getType();

        if ($style === 'kebab') {
            return Str::kebab($name) . '-' . strtolower($type);
        }

        return Str::studly($name) . Str::ucfirst($type);
    }

    /**
     * Get new Cruddy Vue component statements.
     *
     * @return string
     */
    protected function getComponentStatement() : string
    {
        return "Vue.component('" . $this->getComponent('kebab') . "', " . $this->getComponent() . ");\n";
    }

    /**
     * Get new Cruddy Vue import statement.
     *
     * @return string
     */
    protected function getImportStatement() : string
    {
        $name = $this->getResourceName();
        $lowerName = $this->getLowerSingular($name);
        $importString = "import " . $this->getComponent() . " from '@/components/" . $lowerName . "/" . $this->getType() . ".vue';\n";

        return $importString;
    }

    /**
     * Set the component search string.
     *
     * @return self
     */
    protected function setComponentSearch() : self
    {
        $this->componentSearch = $this->getVueComponentSearchString();

        return $this;
    }

    /**
     * Set the has stub file.
     *
     * @return self
     */
    protected function setStubWithPlaceholdersFile() : self
    {
        $this->hasStubFile = File::exists($this->stubLocation);

        return $this;
    }

    /**
     * Determine if the import file has the omponent search string.
     *
     * @return int|false
     */
    protected function componentSearchPosition()
    {
        return strpos($this->stub, $this->componentSearch);
    }

    /**
     * Update the import file.
     *
     * @param  string  $updatedFile
     * @return void
     */
    protected function updateImportFile(string $updatedFile) : void
    {
        File::put($this->getStubLocation(), $updatedFile);
    }

    /**
     * Get the stub file location.
     *
     * @return string
     */
    protected function getStubLocation() : string
    {
        return $this->getVueImportFileLocation();
    }

    /**
     * Update the stub file.
     *
     * @return self
     */
    protected function updateStub() : self
    {
        if ($this->hasStubFile) {
            $this->addImportStatement();
            $this->addComponentStatement();
        }

        return $this;
    }

    /**
     * Add the import statement.
     *
     * @return void
     */
    protected function addImportStatement() : void
    {
        if (!$this->hasImportStatement()) {
            File::prepend($this->stubLocation, $this->importStatement);
        }
    }

    /**
     * Add the component statement.
     *
     * @return void
     */
    protected function addComponentStatement() : void
    {
        $positionInFile = $this->componentSearchPosition();

        if (is_numeric($positionInFile)) {
            $updatedFile = substr_replace($this->stub, $this->componentStatement, $positionInFile, 0);
            $this->updateImportFile($updatedFile);
        }
    }
}