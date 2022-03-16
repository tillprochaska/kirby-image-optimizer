<?php

namespace TillProchaska\KirbyImageOptimizer\Tests;

use Kirby\Cms\App;
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

        if (null === App::plugin('tillprochaska/image-optimizer')) {
            App::plugin('tillprochaska/image-optimizer', require __DIR__.'/../plugin/extensions.php');
        }
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
