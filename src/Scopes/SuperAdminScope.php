<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Rawilk\LaravelBase\Models\Role;

class SuperAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::hasUser() || Auth::user()->isSuperAdmin()) {
            return;
        }

        $builder->whereDoesntHave('roles', fn (Builder $query) => $query->where('name', Role::$superAdminName));
    }
}
