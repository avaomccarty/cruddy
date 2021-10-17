<?php

namespace Cruddy;

use Illuminate\Database\Schema\Builder as BaseBuilder;
use Closure;
use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class Builder extends BaseBuilder
{
    use ConfigTrait;

    /**
     * The console command signature.
     *
     * @var array
     */
    protected $views = [
        'index',
        'create',
        'show',
        'edit',
    ];

    /**
     * Create a new table on the schema.
     *
     * @param  string  $table
     * @param  \Closure  $callback
     * @return void
     */
    public function create($table, Closure $callback)
    {
        $this->createPreHook($table, $callback);
        parent::create($table, $callback);
    }

    /**
     * Prehook for the create method. Calls the Artisan commands needed to create the Cruddy files.
     *
     * @param  string  $table
     * @return void
     */
    protected function createPreHook(string $table, Closure $callback)
    {
        $className = $this->getClassName($table);
        $blueprint = $this->createBlueprint($table, $callback);

        Artisan::call('cruddy:controller', [
            'name' => $className . 'Controller',
            '--resource' => true,
            '--model' => $className, // Note: This should be improved. Use the Cruddy model, not the default.
            '--api' => $this->isApi(),
            '--inputs' => $blueprint->getColumns(),
        ]);

        // Create update request class
        Artisan::call('cruddy:request', [
            'name' => $className,
            'type' => 'update',
            'rules' => $blueprint->getColumns(),
        ]);

        // Create store request class
        Artisan::call('cruddy:request', [
            'name' => $className,
            'type' => 'store',
            'rules' => $blueprint->getColumns(),
        ]);

        Artisan::call('cruddy:route', [
            'name' => $className,
            '--api' => $this->isApi(),
        ]);

        if ($this->needsUI()) {
            foreach ($this->views as $view) {
                if (! $this->isApi() || $view !== 'edit') {
                    // Make standard views
                    Artisan::call('cruddy:view', [
                        'name' => $className,
                        'table' => $table,
                        'type' => $view,
                        'inputs' => $blueprint->getColumns(),
                    ]);
                }

                if ($this->needsVueFrontend()) {
                    if (! $this->isApi() || $view !== 'edit') {
                        // Make Vue views
                        Artisan::call('cruddy:vue-view', [
                            'name' => $className,
                            'table' => $table,
                            'type' => $view,
                        ]);
                    }

                    Artisan::call('cruddy:vue-import', [
                        'name' => $className,
                        'type' => $view,
                    ]);
                }
            }
        }
    }

    /**
     * Get the class name from the $table.
     *
     * @param  string  $table
     * @return string
     */
    protected function getClassName(string $table)
    {
        return Str::studly(Str::singular(trim($table)));
    }
}
