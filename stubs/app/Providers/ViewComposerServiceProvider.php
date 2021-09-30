<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\ViewComposers\SessionAlertViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(
            'layouts.partials.session-alert',
            SessionAlertViewComposer::class,
        );
    }
}
