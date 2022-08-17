<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Roles;

use App\Enums\PermissionEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Roles\CreateRoleAction;
use Rawilk\LaravelBase\LaravelBase;

class Create extends Component
{
    use AuthorizesRequests;

    public array $state = [
        'name' => '',
        'description' => '',
        'permissions' => [],
    ];

    public function createRole(CreateRoleAction $creator)
    {
        $this->authorize(PermissionEnum::ROLES_CREATE->value);

        $this->resetErrorBag();

        $creator($this->state);

        Session::flash('success', __('base::roles.alerts.created'));

        return redirect()->route('admin.roles.index');
    }

    public function render(): View
    {
        return view('laravel-base::livewire.roles.create.index', [
            'permissions' => app(config('permission.models.permission'))::groupedPermissions(),
        ])->layout(LaravelBase::adminViewLayout(), [
            'title' => __('base::roles.create.title'),
        ]);
    }
}
