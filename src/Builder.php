<?php

namespace Cruddy;

use Illuminate\Database\Schema\Builder as BaseBuilder;
use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str

class Builder extends BaseBuilder
{
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
            'name' => $className. 'Controller',
            '--resource' => true,
            '--model' => $className,
            '--api' => config('cruddy.is_api'),
            '--inputs' => $blueprint->getColumns(),
        ]);

        // Create update request class
        Artisan::call('cruddy:request', [
            'name' => $className,
            'type' => 'update',
            'rules' => $blueprint->getColumns(), // Should this be --rules?
        ]);

        // Create store request class
        Artisan::call('cruddy:request', [
            'name' => $className,
            'type' => 'store',
            'rules' => $blueprint->getColumns() // Should this be --rules?
        ]);

        // Note: Commented out for testing. Needs to be updated to not keep inserting resource.
        Artisan::call('cruddy:route', [
            'name' => $className,
            '--api' => config('cruddy.is_api'),
        ]);

        if (config('cruddy.needs_ui')) {
            // Make index view
            Artisan::call('cruddy:view', [
                'name' => $className,
                'table' => $table,
                'type' => 'index',
                'inputs' => $blueprint->getColumns(),
            ]);

            // Make create view
            Artisan::call('cruddy:view', [
                'name' => $className,
                'table' => $table,
                'type' => 'create',
                'inputs' => $blueprint->getColumns(),
            ]);

            // Make show view
            Artisan::call('cruddy:view', [
                'name' => $className,
                'table' => $table,
                'type' => 'show',
                'inputs' => $blueprint->getColumns(),
            ]);

            if (! config('cruddy.is_api')) {
                // Make edit view
                Artisan::call('cruddy:view', [
                    'name' => $className,
                    'table' => $table,
                    'type' => 'edit',
                    'inputs' => $blueprint->getColumns(),
                ]);
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
        return Str::studly(trim($table));
    }
}
