<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

final class BladeComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            // Create a "page" component for pages that don't have full-page livewire components.
            $blade->component('layouts.app.base', 'page');
        });
    }
}
