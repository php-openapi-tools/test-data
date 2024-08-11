<?php

declare(strict_types=1);

namespace OpenAPITools\TestData;

use RecursiveDirectoryIterator;
use SplFileInfo;

use function dirname;

final class Provider
{
    /** @return iterable<string> */
    public static function sets(): iterable
    {
        $iterator = new RecursiveDirectoryIterator(dirname(__FILE__) . '/DataSets/');

        foreach ($iterator as $node) {
            if (! ($node instanceof SplFileInfo) || ! $node->isFile()) {
                continue;
            }

            yield $node->getPathname();
        }
    }
}
