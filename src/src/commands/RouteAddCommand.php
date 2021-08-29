<?php

namespace Cruddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RouteAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cruddy:route
                            {name : The name of the resource that needs new routes}
                            {--api=false : Flag for determining if API only routes need to be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new Cruddy routes';

    /**
     * The name of the new Cruddy resource.
     *
     * @var string
     */
    protected $name;

    // /**
    //  * Create a new command instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     parent::__construct();
    // }

    /**
     * Get new Cruddy resource route.
     *
     * @return string
     */
    protected function getResourceRoute()
    {
        // Note: Check on how Laravel core pluralizes table names
        // Note: Should probably check to make sure that there isn't already a resource route in the file.
        $lowerName = strtolower($this->argument('name')) . 's';
        $ucFirstName = ucfirst($this->argument('name'));
        $routeString = "\n\n" .
                        "// $ucFirstName Resource\n" .
                        "Route::" . $this->getResourceType() . "('$lowerName', 'App\Http\Controllers\\" . $ucFirstName . "Controller')";

        return $routeString . ';';
    }

    /**
     * Get the type of route resource needed.
     *
     * @return string
     */
    public function getResourceType()
    {
        if ($this->option('api')) {
            return 'apiResource';
        }

        return 'resource';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resourceRoute = $this->getResourceRoute();

        $file = $this->option('api') ? 'api' : 'web';

        if (File::exists('routes/' . $file . '.php')) {
            File::append('routes/' . $file . '.php', $resourceRoute);
            echo "Cruddy resource routes added successfully!\n";
        } else {
            echo "No Cruddy resource routes were added.\n";
        }
    }
}
