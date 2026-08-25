<?php

declare(strict_types=1);

namespace OpenAPITools\TestData;

use RecursiveDirectoryIterator;
use SplFileInfo;

use function basename;

use const DIRECTORY_SEPARATOR;

/**
 * Discovers OpenAPI YAML fixtures under {@see DataSet} and yields them for PHPUnit data providers.
 *
 * Only `*.yaml` files in `src/DataSets/` are included. Other files (for example `PLAN.md`) are ignored.
 *
 * @api
 */
final class Provider
{
    /** @return iterable<string, array<DataSet>> Map of data set name to a single-element list (PHPUnit data-provider shape) */
    public static function sets(): iterable
    {
        $iterator = new RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . 'DataSets' . DIRECTORY_SEPARATOR);

        foreach ($iterator as $node) {
            if (! ($node instanceof SplFileInfo) || ! $node->isFile()) {
                /** @infection-ignore-all */
                continue;
            }

            if ($node->getExtension() !== 'yaml') {
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
