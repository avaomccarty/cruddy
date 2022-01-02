<?php

namespace Cruddy\Traits;

use Cruddy\Exceptions\Config\UnknownDatabaseConnection;
use Cruddy\Exceptions\Config\UnknownStubsLocation;
use Cruddy\Exceptions\Config\UnknownVueFolderLocation;
use Cruddy\Exceptions\Config\UnknownVueImportFileLocation;
use Illuminate\Support\Facades\Config;
use PDO;

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
     *
     * @throws \Cruddy\Exceptions\Config\UnknownStubsLocation
     */
    protected function getStubsLocation() : string
    {
        return Config::get('cruddy.stubs_folder') ?? throw new UnknownStubsLocation();
    }

    /**
     * Determine if the frontend is for Vue.
     *
     * @return boolean
     */
    protected function needsVueFrontend() : bool
    {
        return Config::get('cruddy.frontend_scaffolding') === 'vue';
    }

    /**
     * Get the cruddy database connection.
     *
     * @return array
     *
     * @throws \Cruddy\Exceptions\Config\UnknownDatabaseConnection
     */
    protected function getCruddyDatabaseConnection() : array
    {
        return Config::get('cruddy.database.connections.cruddy') ?? throw new UnknownDatabaseConnection();
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
     * Get the Vue folder.
     *
     * @return string
     *
     * @throws \Cruddy\Exceptions\Config\UnknownVueFolderLocation
     */
    protected function getVueFolder() : string
    {
        return Config::get('cruddy.vue_folder') ?? throw new UnknownVueFolderLocation();
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
     * Get the Vue import file location.
     *
     * @return string
     *
     * @throws \Cruddy\Exceptions\Config\UnknownVueImportFileLocation
     */
    public function getVueImportFileLocation() : string
    {
        return Config::get('cruddy.vue_import_file') ?? throw new UnknownVueImportFileLocation();
    }

    /**
     * Get the validation default rule for a column type.
     *
     * @return string
     */
    public function getValidationDefault(string $type) : string
    {
        $validationDefaults = $this->getValidationDefaults();

        return array_key_exists($type, $validationDefaults) ? $validationDefaults[$type] : '';
    }

    /**
     * Get the validation default rule for a column type.
     *
     * @return array
     */
    public function getValidationDefaults() : array
    {
        return Config::get('cruddy.validation_defaults') ?? [];
    }

    /**
     * Get the default input type.
     *
     * @return string
     */
    public function getDefaultForInputType(string $input) : string
    {
        $inputDefaults = $this->getInputDefaults();

        return array_key_exists($input, $inputDefaults) ? $inputDefaults[$input] : '';
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

    /**
     * Get the Vue component search string. 
     *
     * @return string
     */
    public function getVueComponentSearchString() : string
    {
        return Config::get('cruddy.vue_search_string') ?? '';
    }
}