<?php

namespace Cruddy\StubEditors;

use Illuminate\Support\Facades\File;

class RequestStubEditor extends StubEditor
{
    /**
     * The acceptable rule placeholders within a stub.
     *
     * @var array
     */
    public $rulesPlaceholders = [
        'DummyRules',
        '{{ rules }}',
        '{{rules}}'
    ];

    /**
     * The formatting at the end of the line.
     *
     * @var string
     */
    protected $endOfLine = "\n\t\t\t";

    /**
     * Get the stub.
     *
     * @return string
     */
    public function getStubFile() : string
    {
        $stubPath = $this->resolveStubPath($this->getStubsLocation() . '/request.stub');

        return File::get($stubPath);
    }
}