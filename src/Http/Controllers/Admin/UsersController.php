<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers\Admin;

use App\Models\User\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Rawilk\LaravelBase\Enums\HttpStatus;
use Rawilk\LaravelBase\LaravelBase;

class UsersController
{
    public function index(): View
    {
        return view(LaravelBase::configuredView('users.index', 'livewire.admin.users.index.index'));
    }

    public function create(): View
    {
        return view(LaravelBase::configuredView('users.create', 'livewire.admin.users.create.index'));
    }

    public function edit(User $user): View
    {
        return view(LaravelBase::configuredView('users.edit', 'livewire.admin.users.edit.index'), [
            'user' => $user,
        ]);
    }

    public function abilities(User $user): View
    {
        abort_unless(
            Auth::user()->canAny(['assignRolesTo', 'assignPermissionsTo'], $user),
            HttpStatus::Forbidden->value,
        );

        return view(LaravelBase::configuredView('users.abilities', 'livewire.admin.users.edit.abilities'), [
            'user' => $user,
        ]);
    }
}
