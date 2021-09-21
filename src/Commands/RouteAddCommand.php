<?php

namespace Cruddy\Commands;

use Cruddy\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RouteAddCommand extends Command
{
    use CommandTrait;

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

    /**
     * Get new Cruddy resource route.
     *
     * @return string
     */
    protected function getResourceRoute()
    {
        $lowerName = $this->getLowerPlural($this->argument('name'));
        $ucFirstName = Str::ucfirst($this->argument('name'));
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
    protected function getResourceType()
    {
        return $this->option('api') ? 'apiResource' : 'resource';
    }

    /**
     * Get the route file type.
     *
     * @return string
     */
    protected function getRouteFileType()
    {
        return $this->option('api') ? 'api' : 'web';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resourceRoute = $this->getResourceRoute();

        $file = $this->getRouteFileType();

        if (File::exists('routes/' . $file . '.php')) {
            $routeFile = File::get('routes/' . $file . '.php');

            if (strpos($routeFile, $resourceRoute) !== false) {
                echo "No Cruddy resource routes were added.\n";
            } else {
                File::append('routes/' . $file . '.php', $resourceRoute);
                echo "Cruddy resource routes were added successfully!\n";
            }
        } else {
            echo "No Cruddy resource routes were added.\n";
        }
    }
}
