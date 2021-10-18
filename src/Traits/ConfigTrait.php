<?php

namespace Cruddy\Traits;

use Illuminate\Support\Facades\Config;

trait ConfigTrait
{
    /**
     * Get the name for the specific stubs folder based on the frontend scaffolding.
     *
     * @return string
     */
    protected function getFrontendScaffoldingName() : string
    {
        return Config::get('cruddy.frontend_scaffolding') ?? 'default';
    }

    /**
     * Get the location for all of the stubs.
     *
     * @return string
     */
    protected function getStubsLocation() : string
    {
        return Config::get('cruddy.stubs_folder');
    }

    /**
     * Determine if the frontend is for Vue.
     *
     * @return boolean
     */
    protected function needsVueFrontend($test = false) : bool
    {
        return Config::get('cruddy.frontend_scaffolding') === 'vue';
    }

    /**
     * Get the cruddy database connection.
     *
     * @return array
     */
    protected function getCruddyDatabaseConnection() : array
    {
        return Config::get('cruddy.database.connections.cruddy') ?? [];
    }

    /**
     * Get the cruddy database connection.
     *
     * @return void
     */
    protected function setDatabaseConnection() : void
    {
        Config::set('database.connections.cruddy', $this->getCruddyDatabaseConnection());
    }

    /**
     * Get the vue folder.
     *
     * @return string
     */
    protected function getVueFolder() : string
    {
        return Config::get('cruddy.vue_folder') ?? '';
    }

    /**
     * Check if the resource is an API.
     *
     * @return boolean
     */
    public function isApi() : bool
    {
        return (bool)Config::get('cruddy.is_api');
    }

    /**
     * Check if the resource is an API.
     *
     * @return boolean
     */
    public function needsUI() : bool
    {
        return (bool)Config::get('cruddy.needs_ui');
    }

    /**
     * Get the vue import file location.
     *
     * @return string
     */
    public function getVueImportFileLocation() : string
    {
        return Config::get('cruddy.vue_import_file') ?? '';
    }

    /**
     * Get the validation default rule for a column type.
     *
     * @return string
     */
    public function getValidationDefault(string $type) : string
    {
        return Config::get('cruddy.validation_defaults.' . $type) ?? '';
    }

    /**
     * Get the default input type.
     *
     * @return string
     */
    public function getDefaultForInputType(string $input) : string
    {
        return Config::get('cruddy.input_defaults.' . $input) ?? '';
    }

    /**
     * Get the input defaults.
     *
     * @return array
     */
    public function getInputDefaults() : array
    {
        return (array)Config::get('cruddy.input_defaults') ?? [];
    }
}