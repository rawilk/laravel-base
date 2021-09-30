<?php

declare(strict_types=1);

namespace App\Services\Menus\Macros;

use Illuminate\Support\Facades\Config;
use Spatie\Menu\Laravel\Menu;

final class ExpandableMenu extends MenuMacro
{
    public function register(): void
    {
        Menu::macro('expandable', function (null|callable $callback = null) {
            /** @var \Spatie\Menu\Laravel\Menu $this */
            $this->wrap('div', [
                'x-data' => '{ open: false, hasActiveChild: false }',
                'x-bind:aria-expanded' => 'JSON.stringify(open)',
                'x-cloak' => '',
                'x-init' => "
                    open = Array.from(\$el.children)
                        .filter(child => child.classList.contains('active'))
                        .length > 0;

                    hasActiveChild = open;
                ",
            ])
            ->withoutParentTag()
            ->withoutWrapperTag()
            ->setActiveClassOnLink()
            ->setActiveClass(Config::get('site.main_menu.item_active_class') . ' active')
            ->setItemAttribute('x-bind:class', "{ 'hidden': ! open }")
            ->addItemClass(Config::get('site.main_menu.submenu_item_class') . ' ' . Config::get('site.main_menu.item_inactive_class'));

            is_callable($callback) && $callback($this);
        });
    }
}
