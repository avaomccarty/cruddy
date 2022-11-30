<?php

namespace Cruddy\Commands\AddCommands;

class RouteAddCommand extends AddCommand
{
    /**
     * The type of stub editor.
     *
     * @var string
     */
    protected $stubEditorType = 'route';

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
     * The success message.
     *
     * @var string
     */
    protected $successMessage = "Cruddy resource routes were added successfully!\n";

    /**
     * The no routes were added message.
     *
     * @var string
     */
    protected $noRoutesAddedMessage = "No Cruddy resource routes were added.\n";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->hasResourceRoute()) {
            $this->line($this->noRoutesAddedMessage);

            return 1;
        }
        
        parent::handle();
    }
}
