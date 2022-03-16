<?php

beforeEach(function () {
    $this->original = $this->kirby()->site()->homePage()->image('example.svg');
});

it('creates thumbnails with suffix', function () {
    $optimized = $this->original->thumb(['optimize' => true]);
    expect($optimized)->url()->toEndWith('/example-optimized.svg');
});

it('has shorthand method', function () {
    $optimized = $this->original->optimize();
    expect($optimized)->url()->toEndWith('/example-optimized.svg');
});

it('delivers and stores optimized image', function () {
    $optimized = $this->original->thumb(['optimize' => true]);
    $svg = $this->get($optimized->url())->body();

    expect($svg)->toEqual('<svg xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="red"/></svg>');
    expect($optimized)->exists()->toBeTrue();
});

it('delivers original if optimize option is not set', function () {
    $url = $this->original->thumb()->url();
    expect($url)->toEqual($this->original->url());

    $svg = $this->get($url)->body();
    expect($svg)->toEqual("<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">\n     <rect width=\"100%\" height=\"100%\" fill=\"red\" />\n</svg>\n");
});

it('delivers original if optimize option is falsy', function () {
    $url = $this->original->thumb(['optimize' => 0])->url();
    expect($url)->toEqual($this->original->url());

    $svg = $this->get($url)->body();
    expect($svg)->toEqual("<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\">\n     <rect width=\"100%\" height=\"100%\" fill=\"red\" />\n</svg>\n");
});

it('ignores modifications other than optimization for SVG files', function () {
    $thumb = $this->original->thumb(['optimize' => true, 'width' => 900, 'quality' => 50]);
    expect($thumb)->url()->toEndWith('/example-optimized.svg');
});
