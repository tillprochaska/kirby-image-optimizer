<?php

use Kirby\Cms\App;
use Kirby\Cms\FileVersion;
use Kirby\Data\Data;
use Kirby\Filesystem\Filename;
use Kirby\Image\Image;
use Kirby\Toolkit\A;
use Spatie\ImageOptimizer\OptimizerChainFactory;

$defaultComponent = kirby()->component('thumb');

return [
    'fileMethods' => [
        'optimize' => function () {
            return $this->thumb(['optimize' => true]);
        },
    ],

    'components' => [
        'thumb' => function (App $kirby, string $sourcePath, string $destPath, array $options) use ($defaultComponent) {
            $sourceImage = new Image($sourcePath);

            if ('svg' !== $sourceImage->extension()) {
                $defaultComponent($kirby, $sourcePath, $destPath, $options);
            } else {
                $sourceImage->copy($destPath, force: true);
            }

            $optimizerChain = OptimizerChainFactory::create();
            $optimizerChain->optimize($destPath);

            return $destPath;
        },

        'file::version' => function (App $kirby, $file, array $options = []) {
            if (false === $file->isResizable() && 'svg' !== $file->extension()) {
                return $file;
            }

            // SVG files can only be optimized, not resized etc.
            if ('svg' === $file->extension()) {
                $options = A::get($options, ['optimize']);
            }

            $mediaRoot = dirname($file->mediaRoot());

            $template = $options['optimize'] ?? false
                ? '/{{ name }}{{ attributes }}-optimized.{{ extension }}'
                : '/{{ name }}{{ attributes }}.{{ extension }}';

            $thumbRoot = (new Filename($file->root(), $mediaRoot.$template, $options))->toString();
            $thumbName = basename($thumbRoot);

            if (false === file_exists($thumbRoot)) {
                $job = $mediaRoot.'/.jobs/'.$thumbName.'.json';

                try {
                    Data::write($job, array_merge($options, [
                        'filename' => $file->filename(),
                    ]));
                } catch (Throwable) {
                    return $file;
                }
            }

            return new FileVersion([
                'modifications' => $options,
                'original' => $file,
                'root' => $thumbRoot,
                'url' => dirname($file->mediaUrl()).'/'.$thumbName,
            ]);
        },
    ],
];
