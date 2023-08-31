<?php

declare(strict_types=1);

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Rawilk\LaravelBase\Concerns\HasAvatar;
use Rawilk\LaravelBase\Concerns\HasDatesForHumans;
use Rawilk\LaravelBase\Concerns\TwoFactorAuthenticatable;
use Rawilk\LaravelBase\Models\Role;
use Rawilk\LaravelCasters\Casts\Password;
use Rawilk\LaravelCasters\Support\Name;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasAvatar;
    use HasDatesForHumans;
    use HasFactory;
    use HasRoles {
        hasRole as hasRolesHasRole;
        roles as hasRolesRoles;
    }
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'name' => Name::class,
        'password' => Password::class,
    ];

    public function hasRole($roles, string $guard = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->hasRolesHasRole($roles, $guard);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles->containsStrict('name', Role::$superAdminName);
    }

    public function roles(): BelongsToMany
    {
        return $this->hasRolesRoles()->withoutGlobalScopes();
    }

    public function getEditUrlAttribute(): string
    {
        return route('admin.users.edit', $this);
    }

    public function getShowUrlAttribute(): string
    {
        return $this->edit_url;
    }
}
