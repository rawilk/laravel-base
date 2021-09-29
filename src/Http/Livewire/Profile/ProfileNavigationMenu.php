<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProfileNavigationMenu extends Component
{
    public string $view;

    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    public function render(): View
    {
        return view($this->view);
    }
}
