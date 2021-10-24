<?php

namespace Cruddy\Traits;

use Illuminate\Support\Str;

trait RouteAddCommandTrait
{
    use CommandTrait;

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
     * Get resource route.
     *
     * @return string
     */
    protected function getResourceRoute() : string
    {
        $name = $this->getResourceName();
        $lowerPluralName = $this->getLowerPlural($name);
        $ucFirstName = Str::ucfirst($name);

        return "\n\n// $ucFirstName Resource\n"
            . "Route::" . $this->getResourceType() . "('$lowerPluralName', 'App\Http\Controllers\\" . $ucFirstName . "Controller');";
    }

    /**
     * Get the lower plural version of the value.
     *
     * @param  string  $value
     * @return string
     */
    protected function getLowerPlural(string $value) : string
    {
        return strtolower(Str::pluralStudly($value)) ?? '';
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