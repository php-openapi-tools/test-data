<?php

declare(strict_types=1);

namespace OpenAPITools\TestData;

use RecursiveDirectoryIterator;
use SplFileInfo;

use function basename;
use function dirname;

use const DIRECTORY_SEPARATOR;

final class Provider
{
    /** @return iterable<string, array<DataSet>> */
    public static function sets(): iterable
    {
        $iterator = new RecursiveDirectoryIterator(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DataSets');

        foreach ($iterator as $node) {
            if (! ($node instanceof SplFileInfo) || ! $node->isFile()) {
                /** @infection-ignore-all */
                continue;
            }

            $name = basename($node->getPathname(), '.yaml');

            yield $name => [
                new DataSet(
                    $name,
                    $node->getPathname(),
                ),
            ];
        }
    }
}
