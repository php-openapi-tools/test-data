<?php

declare(strict_types=1);

namespace OpenAPITools\Tests\TestData;

use OpenAPITools\TestData\DataSet;
use OpenAPITools\TestData\Provider;
use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\TestUtilities\TestCase;

use function array_keys;
use function array_map;
use function dirname;
use function pathinfo;
use function str_ends_with;

use const DIRECTORY_SEPARATOR;
use const PATHINFO_EXTENSION;

final class RepresentationTest extends TestCase
{
    #[Test]
    public function keys(): void
    {
        self::assertContains(
            'TripleNestedSchema',
            array_keys([...Provider::sets()]),
        );
    }

    #[Test]
    public function names(): void
    {
        self::assertContains(
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

    #[Test]
    public function fileNames(): void
    {
        self::assertContains(
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

    #[Test]
    public function onlyYamlFiles(): void
    {
        foreach ([...Provider::sets()] as [$dataSet]) {
            self::assertSame('yaml', pathinfo($dataSet->fileName, PATHINFO_EXTENSION));
            self::assertFalse(str_ends_with($dataSet->fileName, '.md'));
        }
    }
}
