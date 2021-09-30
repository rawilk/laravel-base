<?php

declare(strict_types=1);

namespace App\Services\Menus;

use App\Services\Menus\Macros\ExpandableMenu;
use Spatie\Menu\Laravel\Facades\Menu;

final class MainMenu
{
    private const MACROS = [
        ExpandableMenu::class,
    ];

    public function __construct(
        private string $iconView,
        private string $submenuView,
        private string $submenuItemView,
    ) {
    }

    public function register(): void
    {
        $this->registerMacros();
        $this->registerMenu();
    }

    private function registerMenu(): void
    {
        $iconView = $this->iconView;
        $submenuView = $this->submenuView;
        $submenuItemView = $this->submenuItemView;

        Menu::macro('main', function () use ($iconView, $submenuView, $submenuItemView) {
            $menu = (new MainMenuItems(
                Menu::new(),
                $iconView,
                $submenuView,
                $submenuItemView,
            ))->register();

            return $menu
                ->withoutWrapperTag()
                ->withoutParentTag()
                ->setActiveFromRequest()
                ->setActiveClassOnLink();
        });
    }

    private function registerMacros(): void
    {
        /** @var \App\Services\Menus\Macros\MenuMacro $macro */
        foreach (self::MACROS as $macro) {
            (new $macro)->register();
        }
    }
}
