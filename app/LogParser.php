<?php

namespace App;

use Illuminate\Support\Str;

class LogParser
{
    public function parse(string $contents)
    {
        $pieces = collect();
        $lines = collect(
            explode("\n", $contents)
        );

        $lines->each(function (string $line) use (&$pieces) {
            if (Str::startsWith($line, '[2019-')) {
                $pieces->push($line);
            } else {
                $length = $pieces->count();
                $currentContent = $pieces->last();

                $pieces->put($length - 1, $currentContent . "\n" . $line);
            }
        });

        return $pieces;
    }
}
