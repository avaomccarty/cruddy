<?php

namespace Cruddy\Commands;

use Cruddy\Traits\RequestMakeCommandTrait;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class RequestMakeCommand extends GeneratorCommand
{
    use RequestMakeCommandTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cruddy:request
                            { name : The name of the resource that needs new request files. }
                            { type=update : The type of request needed. }
                            { rules?* : The validation rules. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Cruddy request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Cruddy request';

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
            ->replaceRules($stub)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() : array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['type', InputArgument::OPTIONAL, 'The type of request class'],
            ['rules', InputArgument::OPTIONAL, 'The validation rules of request class'],
        ];
    }
}
