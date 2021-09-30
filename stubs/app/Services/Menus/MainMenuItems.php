<?php

declare(strict_types=1);

namespace App\Services\Menus;

use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\View;

final class MainMenuItems
{
    private User|Authenticatable|null $user;

    public function __construct(
        private Menu $menu,
        private string $iconView,
        private string $submenuView,
        private string $submenuItemView,
    ) {
        $this->user = Auth::user();
    }

    public function register(): Menu
    {
        $this->addDashboard();

        return $this->menu;
    }

    private function addDashboard(): self
    {
        $this->menu->add(
            View::create($this->iconView, [
                'label' => __('Dashboard'),
                'url' => url('/'),
                'icon' => 'heroicon-o-home',
            ])
        );

        return $this;
    }
}
