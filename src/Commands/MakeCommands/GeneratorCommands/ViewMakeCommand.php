<?php

namespace Cruddy\Commands\MakeCommands\GeneratorCommands;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
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
     * Create a new view make command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setStubEditor();

        $this->stubEditor->setViewType($this->getType());

        if ($this->needsVueFrontend()) {
            $this->stubEditor->setVueData($this->getInputs());
        }
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
}
