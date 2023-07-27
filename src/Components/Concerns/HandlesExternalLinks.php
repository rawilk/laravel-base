<?php

namespace Rawilk\LaravelBase\Components\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property null|string $href
 * @property bool $blockReferrer
 */
trait HandlesExternalLinks
{
    public function isExternalLink(): bool
    {
        return isExternalLink($this->href);
    }

    public function rel(string $userDefinedRel = null): string
    {
        return Arr::toCssClasses([
            'nofollow',
            'noopener',
            'noreferrer' => $this->blockReferrer,
            'external',
            Str::replace(['nofollow', 'noreferrer', 'noopener', 'external'], '', (string) $userDefinedRel),
        ]);
    }
}
