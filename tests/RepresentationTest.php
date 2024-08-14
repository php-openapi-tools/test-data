<?php

declare(strict_types=1);

namespace OpenAPITools\Tests\TestData;

use OpenAPITools\TestData\DataSet;
use OpenAPITools\TestData\Provider;
use WyriHaximus\TestUtilities\TestCase;

use function array_keys;
use function array_map;
use function dirname;

use const DIRECTORY_SEPARATOR;

final class RepresentationTest extends TestCase
{
    /** @test */
    public function keys(): void
    {
        static::assertContains(
            'TripleNestedSchema',
            array_keys([...Provider::sets()]),
        );
    }

    /** @test */
    public function names(): void
    {
        static::assertContains(
            'TripleNestedSchema',
            array_map(
                static fn (DataSet $dataSet): string => $dataSet->name,
                array_map(
                    static fn (array $arguments): DataSet => $arguments[0],
                    [...Provider::sets()],
                ),
            ),
        );
    }

    /** @test */
    public function fileNames(): void
    {
        static::assertContains(
            dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'DataSets' . DIRECTORY_SEPARATOR . 'TripleNestedSchema.yaml',
            array_map(
                static fn (DataSet $dataSet): string => $dataSet->fileName,
                array_map(
                    static fn (array $arguments): DataSet => $arguments[0],
                    [...Provider::sets()],
                ),
            ),
        );
    }
}
