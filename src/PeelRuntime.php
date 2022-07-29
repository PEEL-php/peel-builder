<?php

namespace Peel\Builder;

use DOMDocument;
use Peel\Builder\Parser\PeelParser;

class PeelRuntime
{
    public static function includePeelFile(string $filePath)
    {
        self::includePeelString(file_get_contents($filePath));
    }

    public static function includePeelString(string $code)
    {
        $parsed = PeelParser::Parse($code);
        $php = $parsed->toPhp();
        eval($php);
    }
}