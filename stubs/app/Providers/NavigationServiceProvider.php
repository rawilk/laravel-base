<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Menus\MainMenu;
use Illuminate\Support\ServiceProvider;

final class NavigationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        (new MainMenu(
            'layouts.app.partials.menu-item-with-icon',
            'layouts.app.partials.submenu-label',
            'layouts.app.partials.submenu-item',
        ))->register();
    }
}
