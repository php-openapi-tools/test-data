<?php

declare(strict_types=1);

namespace OpenAPITools\TestData;

/**
 * A single OpenAPI fixture exposed to downstream PHPOpenAPITools packages.
 *
 * @api
 */
final readonly class DataSet
{
    /**
     * @param string $name     PascalCase identifier derived from the YAML file name (without extension)
     * @param string $fileName Absolute path to the OpenAPI YAML file on disk
     */
    public function __construct(
        public string $name,
        public string $fileName,
    ) {
    }
}
