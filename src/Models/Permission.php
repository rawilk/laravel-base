<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Models;

use App\Enums\PermissionEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * @property string $id
 * @property string $name
 * @property string $guard_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rawilk\LaravelBase\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rawilk\LaravelBase\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 *
 * @method \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
class Permission extends BasePermission
{
    use HasUuids;

    protected $hidden = ['pivot'];

    protected $touches = ['roles'];

    /*
     * We will prefer to use the description provided by the enum, so we can
     * translate it easier, if needed.
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => PermissionEnum::tryFrom($this->name)?->description(),
        )->shouldCache();
    }

    /**
     * Get all the permissions grouped by the first part
     * of their name. Useful for rendering them
     * in groups in the UI.
     */
    public static function groupedPermissions(): Collection
    {
        return static::query()
            ->get(['id', 'name'])
            ->sortBy('name')
            ->groupBy(function ($permission) {
                if (! Str::contains($permission->name, '.')) {
                    return __('base::permissions.uncategorized');
                }

                $group = Str::of($permission->name)
                    ->explode('.', 2)
                    ->first();

                return Str::of($group)
                    ->explode('_')
                    ->join(' ');
            });
    }
}
