<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RouteAddCommand extends Command
{
    use CommandTrait;

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
     * @var \Cruddy\StubEditors\StubEditor|null
     */
    protected $stubEditor;

        /**
     * The name of the resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The default resource type.
     *
     * @var string
     */
    protected $defaultResourceType = 'resource';

    /**
     * The default route file.
     * 
     * @var string
     */
    protected $defaultRouteFileName = 'web';

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
        $resourceRoute = $this->getResourceRoute();
        $file = $this->getRouteFile();

        if (File::exists($file)) {
            if (strpos(File::get($file), $resourceRoute) !== false) {
                $this->line($this->noRoutesAddedMessage);
            } else {
                File::append($file, $resourceRoute);
                $this->line($this->successMessage);
            }
        } else {
            $this->line($this->noRoutesAddedMessage);
        }
    }

    /**
     * Get resource route.
     *
     * @return string
     */
    protected function getResourceRoute() : string
    {
        $name = $this->getResourceName();
        $lowerPluralName = $this->getLowerPlural($name);
        $ucFirstName = ucfirst($name);

        return "\n\n// $ucFirstName Resource\n" . 
            "Route::" . $this->getResourceType() . "('$lowerPluralName', 'App\Http\Controllers\\" . $ucFirstName . "Controller');";
    }

    /**
     * Get the type of route resource needed.
     *
     * @return string
     */
    protected function getResourceType() : string
    {
        return $this->getApi() ? 'apiResource' : $this->defaultResourceType;
    }

    /**
     * Get the route file type.
     *
     * @return string
     */
    protected function getRouteFileName() : string
    {
        return $this->getApi() ? 'api' : $this->defaultRouteFileName;
    }

    /**
     * Get the route file.
     *
     * @return string
     */
    protected function getRouteFile() : string
    {
        return 'routes/' . $this->getRouteFileName() .  '.php';
    }
}
