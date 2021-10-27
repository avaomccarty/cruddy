<?php

namespace Cruddy;

use Illuminate\Database\Schema\Builder as BaseBuilder;
use Closure;
use Cruddy\Traits\CommandTrait;
use Cruddy\Traits\ConfigTrait;
use Illuminate\Support\Facades\Artisan;

class Builder extends BaseBuilder
{
    use ConfigTrait, CommandTrait;

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
     * @var array
     */
    protected $columns;

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

        Artisan::call('cruddy:controller', [
            'name' => $this->className . 'Controller',
            '--resource' => true,
            '--model' => $this->className,
            '--api' => $this->isApi(),
            'inputs' => $this->columns,
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
            'rules' => $this->columns,
        ]);

        Artisan::call('cruddy:route', [
            'name' => $this->className,
            '--api' => $this->isApi(),
        ]);

        if ($this->needsUI()) {
            $this->createFrontendViews();
        }
    }

    /**
     * Create the frontend views.
     *
     * @return void
     */
    protected function createFrontendViews() : void
    {
        foreach ($this->views as $view) {
            if ($this->shouldHaveEditView($view)) {
                // Make standard view
                Artisan::call('cruddy:view', [
                    'name' => $this->className,
                    'table' => $this->table,
                    'type' => $view,
                    'inputs' => $this->columns,
                ]);
            }

            if ($this->needsVueFrontend()) {
                if ($this->shouldHaveEditView($view)) {
                    // Make Vue view
                    Artisan::call('cruddy:vue-view', [
                        'name' => $this->className,
                        'table' => $this->table,
                        'type' => $view,
                    ]);
                }

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
