<?php

declare(strict_types=1);

namespace OpenAPITools\Tests\TestData;

use OpenAPITools\TestData\Provider;
use WyriHaximus\TestUtilities\TestCase;

use function dirname;

use const DIRECTORY_SEPARATOR;

final class RepresentationTest extends TestCase
{
    /** @test */
    public function basic(): void
    {
        static::assertContains(dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'DataSets' . DIRECTORY_SEPARATOR . 'Basic.yaml', Provider::sets());
    }
}
