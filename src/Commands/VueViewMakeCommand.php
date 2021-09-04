<?php

namespace Cruddy\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class VueViewMakeCommand extends GeneratorCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:vue-view {name} {table} {type=index}';

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
     * The acceptable types of views.
     *
     * @var array
     */
    protected $types = [
        'index',
        'create',
        'show',
        'edit',
    ];

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath(Config::get('cruddy.stubs_folder') . '/views/vue/page.stub');
    }

    /**
     * Get the type of request being created.
     *
     * @return string
     */
    public function getType()
    {
        if (in_array($this->argument('type'), $this->types)) {
            return $this->argument('type');
        }

        return $this->types[0];
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
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceModelVariable($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the model variable for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceModelVariable(&$stub)
    {
        $modelVariable = lcfirst(class_basename($this->argument('name')));

        $stub = str_replace(['DummyModelVariable', '{{ modelVariable }}', '{{modelVariable}}'], $modelVariable, $stub);

        return $this;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = strtolower($name);

        return str_replace('\\', '/', $name) . '/' . $this->getType() . '.blade.php';
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\resources\views';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
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
    protected function getClassName()
    {
        $table = $this->argument('table');
        return strtolower(Str::studly(Str::singular(trim($table))));
    }
}
