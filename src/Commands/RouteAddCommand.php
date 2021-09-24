<?php

namespace Cruddy\Commands;

use Cruddy\Traits\RouteAddCommandTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RouteAddCommand extends Command
{
    use RouteAddCommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cruddy:route
                            {name : The name of the resource that needs new routes}
                            {--api : Flag for determining if API only routes need to be created.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new Cruddy routes';

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
}
