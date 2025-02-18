<?php

declare(strict_types=1);

namespace RPurinton\Bunny\Test\Library;

final class Paths
{
    public static function getTestsRootPath(): string
    {
        return dirname(__DIR__);
    }
}
