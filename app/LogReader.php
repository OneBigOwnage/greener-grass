<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class LogReader
{
    /**
     * The log parser, to do some of the heavy lifting for us.
     *
     * @var LogParser
     */
    private $parser;

    /**
     * The file system adapter.
     *
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * Create a new instance.
     *
     * @param  LogParser  $parser     The log parser.
     * @param  FileSystem $fileSystem The file system adapter.
     *
     * @return void
     */
    public function __construct(LogParser $parser, FileSystem $fileSystem)
    {
        $this->parser = $parser;
        $this->fileSystem = $fileSystem;
    }

    public function getLogs()
    {
        return Collection::make($this->fileSystem->files())->filter(function (string $fileName) {
            return Str::endsWith($fileName, 'log');
        });
    }

    public function getLatestLog()
    {
        return $this->getLogs()->sortBy(function ($fileName) {
            $this->fileSystem->lastModified($fileName);
        })->last();
    }

    public function parseLatestLog()
    {
        return $this->parser->parse(
            $this->fileSystem->get($this->getLatestLog())
        );
    }
}
