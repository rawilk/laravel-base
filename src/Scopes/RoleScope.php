<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Rawilk\LaravelBase\Models\Role;

class RoleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! $this->shouldApplyScope()) {
            return;
        }

        $builder->where('name', '!=', Role::$superAdminName);
    }

    protected function shouldApplyScope(): bool
    {
        if (! Auth::hasUser()) {
            return false;
        }

        if (! method_exists(Auth::user(), 'isSuperAdmin')) {
            return false;
        }

        return ! Auth::user()->isSuperAdmin();
    }
}
