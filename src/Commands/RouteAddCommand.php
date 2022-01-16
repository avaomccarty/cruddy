<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConsoleCommandTrait;
use Illuminate\Console\Command;

class RouteAddCommand extends Command
{
    use CommandTrait, ConsoleCommandTrait;

    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'route';

    /**
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cruddy:route
                            { name : The name of the resource that needs new routes. }
                            { --api : Flag for determining if API only routes need to be created. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new Cruddy routes';

    /**
     * The stub editor.
     *
     * @var \Cruddy\StubEditors\RouteStubEditor|null
     */
    protected $stubEditor;

    /**
     * The constructor method.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setStubEditor();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->stubEditor->addResourceRoute();
    }
}
