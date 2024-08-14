<?php

declare(strict_types=1);

namespace OpenAPITools\TestData;

final readonly class DataSet
{
    public function __construct(
        public string $name,
        public string $fileName,
    ) {
    }
}
