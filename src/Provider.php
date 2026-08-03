<?php

declare(strict_types=1);

namespace OpenAPITools\TestData;

use RecursiveDirectoryIterator;
use SplFileInfo;

use function basename;

use const DIRECTORY_SEPARATOR;

/** @api */
final class Provider
{
    /** @return iterable<string, array<DataSet>> */
    public static function sets(): iterable
    {
        $iterator = new RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . 'DataSets' . DIRECTORY_SEPARATOR);

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
