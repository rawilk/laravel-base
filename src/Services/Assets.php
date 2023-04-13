<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services;

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
        $nonce = $this->getNonce($options);

        $manifest = json_decode(file_get_contents(__DIR__ . '/../../dist/assets/manifest.json'), true);
        $versionedFileName = ltrim($manifest['/assets/laravel-base.js'], '/');

        $fullAssetPath = "{$assetsUrl}/laravel-base/{$versionedFileName}";

        return <<<HTML
        <script src="{$fullAssetPath}" data-turbolinks-eval="false" data-turbo-eval="false" {$nonce}></script>
        HTML;
    }

    private function getNonce(array $options): string
    {
        if (isset($options['nonce'])) {
            return "nonce=\"{$options['nonce']}\"";
        }

        if ($nonce = cspNonce()) {
            return "nonce=\"{$nonce}\"";
        }

        return '';
    }
}
