<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Roles;

use App\Enums\PermissionEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithFileUploads;
use Rawilk\LaravelBase\Http\Livewire\DataTable\ImportsModels;
use Rawilk\LaravelBase\Imports\Roles\RolesImport;

class Import extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;
    use ImportsModels;

    public array $fieldColumnMap = [
        'name' => '',
        'description' => '',
        'permissions' => '',
    ];

    protected $rules = [
        'fieldColumnMap.name' => 'required',
    ];

    protected $customAttributes = [
        'fieldColumnMap.name' => 'name',
    ];

    public function fieldsToMap(): array
    {
        return [
            'name' => [
                'label' => __('base::roles.labels.name'),
                'required' => true,
            ],
            'description' => [
                'label' => __('base::roles.labels.description'),
                'required' => false,
            ],
            'permissions' => [
                'label' => __('base::roles.labels.permissions'),
                'required' => false,
                'hint' => __('base::roles.import.permissions_hint'),
            ],
        ];
    }

    public function import(): void
    {
        if (! Auth::user()->canAny([PermissionEnum::ROLES_CREATE->value, PermissionEnum::ROLES_EDIT->value])) {
            return;
        }

        $this->validate(null, null, $this->customAttributes);

        $import = (new RolesImport)
            ->usingMap($this->fieldColumnMap);

        $import->import($this->upload);

        $this->reset();

        $this->emit('refresh-roles');

        $this->notify(
            Lang::choice('base::roles.alerts.import_success', $import->count(), ['count' => $import->count()])
        );
    }

    public function render(): View
    {
        return view('laravel-base::livewire.roles.index.partials.import');
    }

    protected function guesses(): array
    {
        return [
            'name' => ['name', 'title'],
            'description' => ['description', 'desc', 'about'],
            'permissions' => ['permissions', 'perms', 'abilities'],
        ];
    }

    public static function importId(): string
    {
        return 'rolesImport';
    }
}
