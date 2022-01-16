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
        $file = $this->option('pivot') ? '/model.pivot.stub' : '/model.stub';
        $stubPath = $this->resolveStubPath($this->getStubsLocation() . $file);

        return File::get($stubPath);
    }
}