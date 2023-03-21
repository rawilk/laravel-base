<?php

declare(strict_types=1);

use Rawilk\LaravelBase\Facades\LaravelBaseAssets;

it('outputs the script source', function () {
    $this->assertStringContainsString(
        '<script src="/laravel-base/assets/laravel-base.js?',
        LaravelBaseAssets::javaScript(),
    );
});

it('outputs a comment when app is in debug mode', function () {
    config()->set('app.debug', true);

    $this->assertStringContainsString(
        '<!-- LaravelBase Scripts -->',
        LaravelBaseAssets::javaScript(),
    );
});

it('does not output a comment when not in debug mode', function () {
    config()->set('app.debug', false);

    $this->assertStringNotContainsString(
        '<!-- LaravelBase Scripts -->',
        LaravelBaseAssets::javaScript(),
    );
});

it('can set a custom asset url', function () {
    config()->set('laravel-base.asset_url', 'https://example.com');

    $this->assertStringContainsString(
        '<script src="https://example.com/laravel-base/assets/laravel-base.js?',
        LaravelBaseAssets::javaScript(),
    );
});

it('accepts an asset url as an argument', function () {
    $this->assertStringContainsString(
        '<script src="https://example.com/laravel-base/assets/laravel-base.js?',
        LaravelBaseAssets::javaScript(['asset_url' => 'https://example.com']),
    );
});
