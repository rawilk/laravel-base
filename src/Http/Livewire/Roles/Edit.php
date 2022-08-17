<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Roles;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Roles\UpdateRoleAction;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Models\Role as RoleModel;
use Spatie\Permission\Contracts\Role;

class Edit extends Component
{
    use AuthorizesRequests;

    public Role $role;

    public array $state;

    public function updateDetails(UpdateRoleAction $updater): void
    {
        $this->authorize('edit', $this->role);

        $this->resetErrorBag();

        $updater($this->role, $this->state);

        $this->emitSelf('details.updated');
    }

    public function updatePermissions(): void
    {
        $this->authorize('editPermissions', $this->role);

        // The permissions of the "admin" role should never be modified.
        if ($this->role->name === RoleModel::$adminName) {
            return;
        }

        $this->resetErrorBag('permissions');

        $permissions = Validator::make($this->state, [
            'permissions' => ['array'],
        ])->validate();

        $this->role->syncPermissions($permissions);

        $this->emitSelf('permissions.updated');
    }

    public function mount(): void
    {
        $this->state = [
            'description' => $this->role->description,
            'permissions' => array_map(
                'strval',
                $this->role->getAllPermissions()->pluck('id')->toArray()
            ),
        ];
    }

    public function render(): View
    {
        return view('laravel-base::livewire.roles.edit.index', [
            'permissions' => app(config('permission.models.permission'))::groupedPermissions(),
        ])->layout(LaravelBase::adminViewLayout(), [
            'title' => pageTitle($this->role->name, __('base::roles.edit.title')),
        ]);
    }
}
