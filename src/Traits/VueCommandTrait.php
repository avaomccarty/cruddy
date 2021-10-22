<?php

namespace Cruddy\Traits;

use Illuminate\Support\Str;

trait VueCommandTrait {

    use CommandTrait;

    /**
     * Get the studly component name.
     *
     * @param  string  $name
     * @return string
     */
    public function getStudlyComponentName() : string
    {
        $studylyTableName = $this->getStudlySingular($this->getTableName());
        $ucFirstType = Str::ucfirst($this->getType());

        return $studylyTableName . $ucFirstType;
    }
}