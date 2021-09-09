<?php

namespace Rawilk\LaravelBase\Http\Livewire\DataTable;

use Rawilk\LaravelBase\Http\Livewire\DataTable\Highlighters\StringHighlighter;
use Rawilk\LaravelBase\Http\Livewire\DataTable\Highlighters\ViewHighlighter;

trait WithHighlighting
{
    protected static array $highlighters = [
        'string' => StringHighlighter::class,
        'view' => ViewHighlighter::class,
    ];

    public function highlight($content, null | string $filterKey = 'search', string $highlighter = 'string')
    {
        if (! $content || ! ($this->filters[$filterKey] ?? '')) {
            return (string) $content;
        }

        $highlighter = static::$highlighters[$highlighter] ?? StringHighlighter::class;

        return (new $highlighter)->highlight($content, $this->filters[$filterKey] ?? '');
    }
}
