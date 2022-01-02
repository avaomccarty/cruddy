<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\File;

class ModelStubEditor extends StubEditor
{
    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        $stubPath = $this->resolveStubPath($this->getStubsLocation() . '/model.stub');

        return File::get($stubPath);
    }
}