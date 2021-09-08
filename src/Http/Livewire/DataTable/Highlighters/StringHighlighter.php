<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\DataTable\Highlighters;

use Rawilk\LaravelBase\Contracts\Highlighter;

final class StringHighlighter implements Highlighter
{
    public function highlight($value, $search)
    {
        preg_match_all('#' . preg_quote($search) . '#i', $value, $matches);

        $matches = collect($matches[0] ?? [])->unique();

        foreach ($matches as $match) {
            $value = str_replace($match, view('laravel-base::partials.highlighters.highlight', ['slot' => $match])->render(), $value);
        }

        return str_replace(PHP_EOL, '', $value);
    }
}
