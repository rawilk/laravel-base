<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\DataTable\Highlighters;

use DOMDocument;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Rawilk\LaravelBase\Contracts\Highlighter;

final class ViewHighlighter implements Highlighter
{
    public function highlight($value, $search)
    {
        $slot = $value instanceof View ? $value->gatherData()['slot'] : $value;

        $dom = new DOMDocument;
        $dom->loadHTML($slot, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        preg_match_all('#' . preg_quote($search) . '#i', $slot, $matches);

        $matches = collect($matches[0] ?? [])->unique();

        $this->replaceTextInNodes($dom->childNodes, $matches);

        $html = str_replace(PHP_EOL, '', html_entity_decode($dom->saveHTML()));

        return $value instanceof View
            ? $value->with(['slot' => $html])
            : $html;
    }

    private function replaceTextInNodes($childNodes, Collection $matches): void
    {
        /** @var \DOMDocumentType $node */
        foreach ($childNodes as $node) {
            if ($node->hasChildNodes()) {
                $this->replaceTextInNodes($node->childNodes, $matches);
            } else {
                $this->replaceTextInNode($node, $matches);
            }
        }
    }

    /**
     * @param \DOMText|\DOMDocumentType $node
     * @param \Illuminate\Support\Collection $matches
     */
    private function replaceTextInNode($node, Collection $matches): void
    {
        foreach ($matches as $match) {
            $node->textContent = str_replace(
                $match,
                view('laravel-base::partials.highlighters.highlight', ['slot' => $match])->render(),
                $node->textContent,
            );
        }
    }
}
