<?php

namespace Cruddy;

use Illuminate\Database\Schema\Builder as BaseBuilder;
use Closure;
use Cruddy\Traits\CommandTrait;
use Illuminate\Support\Facades\Artisan;

class Builder extends BaseBuilder
{
    use CommandTrait;

    /**
     * The console command signature.
     *
     * @var string[]
     */
    protected $views = [
        'index',
        'create',
        'show',
        'edit',
    ];

    /**
     * The table.
     *
     * @var string
     */
    protected $table;

    /**
     * The class name.
     *
     * @var string
     */
    protected $className;

    /**
     * The blueprint.
     *
     * @var \Illuminate\Database\Schema\Blueprint
     */
    protected $blueprint;

    /**
     * The migration columns.
     *
     * @var \Illuminate\Database\Schema\ColumnDefinition[]
     */
    protected $columns;

    /**
     * The migration commands.
     *
     * @var \Cruddy\ForeignKeyDefinition[]
     */
    protected $commands;

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
     * @param  \Closure  $callback
     * @return void
     */
    protected function createPreHook(string $table, Closure $callback) : void
    {
        $this->table = $table;
        $this->className = $this->getStudlySingular($this->table);
        $this->blueprint = $this->createBlueprint($this->table, $callback);
        $this->columns = $this->blueprint->getColumns();
        $this->commands = $this->blueprint->getCommands();
        $rules = array_merge($this->columns, $this->commands);

        Artisan::call('cruddy:controller', [
            'name' => $this->className . 'Controller',
            '--resource' => true,
            '--model' => $this->className,
            '--api' => $this->isApi(),
            '--inputs' => $this->columns,
            '--commands' => $this->commands,
        ]);

        // Create update request class
        Artisan::call('cruddy:request', [
            'name' => $this->className,
            'type' => 'update',
            'rules' => $this->columns,
        ]);

        // Create store request class
        Artisan::call('cruddy:request', [
            'name' => $this->className,
            'type' => 'store',
            'rules' => $rules,
        ]);

        // Create route class
        Artisan::call('cruddy:route', [
            'name' => $this->className,
            '--api' => $this->isApi(),
        ]);

        if ($this->needsUI()) {
            $this->createFrontendViews();
        }
    }

    /**
     * Create a new command set with a Closure.
     *
     * @param  string  $table
     * @param  \Closure|null  $callback
     * @return \Cruddy\Blueprint
     */
    protected function createBlueprint($table, Closure $callback = null)
    {
        $prefix = $this->connection->getConfig('prefix_indexes')
                    ? $this->connection->getConfig('prefix')
                    : '';

        if (isset($this->resolver)) {
            return call_user_func($this->resolver, $table, $callback, $prefix);
        }

        return new Blueprint($table, $callback, $prefix);
    }

    /**
     * Create the frontend views.
     *
     * @return void
     */
    protected function createFrontendViews() : void
    {
        foreach ($this->views as $view) {
            if ($view !== 'edit' || $this->shouldHaveEditView($view)) {
                // Make standard view
                Artisan::call('cruddy:view', [
                    'name' => $this->className,
                    'table' => $this->table,
                    'type' => $view,
                    'inputs' => $this->columns,
                    '--force' => true,
                ]);
            }

            if ($this->needsVueFrontend()) {
                Artisan::call('cruddy:vue-import', [
                    'name' => $this->className,
                    'type' => $view,
                ]);
            }
        }
    }

    /**
     * Determine if an edit view is needed.
     *
     * @param  string  $view
     * @return bool
     */
    protected function shouldHaveEditView(string $view) : bool
    {
        return ! $this->isApi() || $view !== 'edit';
    }
}
