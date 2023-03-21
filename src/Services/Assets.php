<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services;

use function config;
use function rtrim;

final class Assets
{
    public function javaScript(array $options = []): string
    {
        $html = config('app.debug') ? ['<!-- LaravelBase Scripts -->'] : [];

        $html[] = $this->javaScriptAssets($options);

        return implode(PHP_EOL, $html);
    }

    private function javaScriptAssets(array $options = []): string
    {
        $assetsUrl = config('laravel-base.asset_url') ?: rtrim($options['asset_url'] ?? '', '/');

        $manifest = json_decode(file_get_contents(__DIR__ . '/../../dist/mix-manifest.json'), true);
        $versionedFileName = ltrim($manifest['/assets/laravel-base.js'], '/');

        $fullAssetPath = "{$assetsUrl}/laravel-base/{$versionedFileName}";

        return <<<HTML
        <script src="{$fullAssetPath}" data-turbolinks-eval="false" data-turbo-eval="false"></script>
        HTML;
    }
}
