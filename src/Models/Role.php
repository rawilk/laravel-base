<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Concerns\HasDatesForHumans;
use Rawilk\LaravelBase\Events\Roles\RoleWasDeletedEvent;
use Rawilk\LaravelBase\Scopes\RoleScope;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * @property string $id
 * @property string $name
 * @property string $guard_name
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $created_at_for_humans
 * @property string $edit_url
 * @property string|null $updated_at_for_humans
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rawilk\LaravelBase\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User\User[] $users
 *
 * @method static Builder|Role canBeDefault()
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role permission($permissions)
 * @method static Builder|Role query()
 *
 * @mixin \Eloquent
 */
class Role extends BaseRole
{
    use HasDatesForHumans;
    use HasUuids;

    /** @var int */
    public const MAX_DESCRIPTION_LENGTH = 100;

    protected $hidden = ['pivot'];

    // Protected Roles...
    public static $superAdminName = 'Super Admin';

    public static $adminName = 'Admin';

    public static $userName = 'User';

    public static $protectedRoleNames;

    public static $defaultRoleName;

    /**
     * Determine the name of the role that should be assigned as
     * the "default" user role.
     */
    public static function defaultRoleName(): string
    {
        return (string) (static::$defaultRoleName ?? static::$userName);
    }

    /**
     * Get a list of role names that should not be allowed to be removed
     * from the application.
     */
    public static function protectedRoleNames(): array
    {
        if (is_array(static::$protectedRoleNames)) {
            return static::$protectedRoleNames;
        }

        return [
            static::$superAdminName,
            static::$adminName,
            static::$userName,
        ];
    }

    /**
     * Determine if the current role is considered the "super admin" role
     * of the application.
     */
    public function isSuperAdminRole(): bool
    {
        return $this->name === static::$superAdminName;
    }

    /**
     * Determine if the current role is considered a "protected" role in the application.
     */
    public function isProtected(): bool
    {
        return in_array($this->name, static::protectedRoleNames(), true);
    }

    public function hasPermissionTo($permission): bool
    {
        // The "super admin" role has every permission in the application.
        if ($this->isSuperAdminRole()) {
            return true;
        }

        return parent::hasPermissionTo($permission);
    }

    /**
     * Give all the stored permissions to the role.
     */
    public function giveAllPermissions(): void
    {
        $ids = app(Config::get('permission.models.permission', Permission::class))->pluck('id');

        $this->syncPermissions($ids);
    }

    public function scopeCanBeDefault(Builder $query): void
    {
        $query->whereNotIn('name', [
            static::$superAdminName,
            static::$adminName,
        ]);
    }

    protected function editUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('admin.roles.edit', $this),
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function (string $name): string {
                // Name cannot be modified on existing roles.
                if ($this->getKey()) {
                    return $this->getOriginal('name');
                }

                return $name;
            },
        );
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new RoleScope);

        // Double check to ensure the user cannot edit this role if they
        // not have permission to.
        static::updating(static function (Role $role) {
            if (Auth::check() && ! Auth::user()->can('edit', $role)) {
                return false;
            }
        });

        // Deleting callback is here to ensure protected roles cannot be deleted when
        // performing "bulk deletes".
        static::deleting(static function (Role $role) {
            if (Auth::check() && ! Auth::user()->can('delete', $role)) {
                return false;
            }
        });

        static::deleted(static function (Role $role) {
            event(new RoleWasDeletedEvent($role));
        });
    }

    /*
     * This code can be removed once Spatie releases v6 of the permissions package.
     */
    public static function findById($id, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam([(new static())->getKeyName() => $id, 'guard_name' => $guardName]);

        if (! $role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    public static function findByName(string $name, $guardName = null): RoleContract
    {
        if (static::isUid($name)) {
            return static::findById($name, $guardName);
        }

        return parent::findByName($name, $guardName); // TODO: Change the autogenerated stub
    }

    public static function isUid($value): bool
    {
        if (! is_string($value) || empty(trim($value))) {
            return false;
        }

        if (Str::isUuid($value)) {
            return true;
        }

        if (Str::isUlid($value)) {
            return true;
        }

        return false;
    }
}
