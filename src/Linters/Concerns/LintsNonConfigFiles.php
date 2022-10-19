<?php

namespace Tighten\TLint\Linters\Concerns;

trait LintsNonConfigFiles
{
    public static function appliesToPath(string $path, array $configPaths): bool
    {
        return strpos($path, DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR) === false;
    }
}
