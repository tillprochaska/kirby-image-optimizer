<?php

namespace TillProchaska\KirbyImageOptimizer\Tests;

use Kirby\Filesystem\Dir;
use TillProchaska\KirbyTestUtils\TestCase as BaseTestCase;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends BaseTestCase
{
    protected function beforeKirbyInit(): void
    {
        Dir::remove(__DIR__.'/support/kirby/media');
    }

    protected function kirbyProps(): array
    {
        return [
            'roots' => [
                'index' => __DIR__.'/support/kirby',
            ],
        ];
    }
}
