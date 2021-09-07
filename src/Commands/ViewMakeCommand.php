<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Exception;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

class ViewMakeCommand extends GeneratorCommand
{
    use CommandTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:view {name} {table} {type=index} {inputs?*}';

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

        return $this->replaceNamespace($stub, $name)
            ->replaceInputs($stub)
            ->replaceFormAction($stub)
            ->replaceFormEditUrl($stub)
            ->replaceFormCancelUrl($stub)
            ->replaceModelVariable($stub)
            ->replaceVueComponentName($stub)
            ->replaceVueData($stub)
            ->replaceVuePostData($stub)
            ->replaceClass($stub, $name);
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
     * Get the class name from the table argument.
     *
     * @return string
     */
    protected function getClassName() : string
    {
        $table = $this->getTable();

        if (!empty($table)) {
            return strtolower(Str::studly(Str::singular(trim($table))));
        }

        throw new Exception('Please provide a table.');
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

        if (Config::get('cruddy.frontend_scaffolding') === 'vue') {
            return Config::get('cruddy.vue_folder') . '/' . $this->getClassName() . '/' . $this->getType() . '.vue';
        }

        if (Config::get('cruddy.default_frontend_scaffolding') === 'livewire') {
            $name = str_replace('views\\', 'views\\livewire\\', $name);
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
