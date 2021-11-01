<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Roles;

use App\Support\PermissionName;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Rawilk\LaravelBase\Exports\Roles\RolesExport;
use Rawilk\LaravelBase\Http\Livewire\DataTable\HasDataTable;
use Rawilk\LaravelBase\Http\Livewire\DataTable\WithHighlighting;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Models\Role as RoleModel;
use Spatie\Permission\Contracts\Role;

/**
 * @property-read \Spatie\Permission\Contracts\Role $roleModel
 */
class Index extends Component
{
    use AuthorizesRequests;
    use HasDataTable, WithHighlighting;

    public bool $showDelete = false;
    public bool $showDeleteAll = false;
    public null|Role $deleting = null;
    protected bool $isExporting = false;

    private const SELECTABLE_COLUMNS = ['id', 'name', 'description', 'created_at', 'updated_at'];

    public array $filters = [
        'search' => '',
        'created-min' => '',
        'created-max' => '',
        'updated-min' => '',
        'updated-max' => '',
    ];

    protected $listeners = [
        'refresh-roles' => '$refresh',
    ];

    public function confirmDelete($roleId): void
    {
        $this->deleting = app(config('permission.models.role'))->findOrFail($roleId);

        $this->showDelete = true;
    }

    public function deleteRole(): void
    {
        if (! $this->deleting) {
            return;
        }

        $this->authorize('delete', $this->deleting);

        $this->deleting->delete();

        $this->notify(__('laravel-base::roles.alerts.deleted', ['name' => $this->deleting->name]));

        $this->showDelete = false;
        $this->deleting = null;
    }

    public function deleteSelected(): void
    {
        // Each role will be authorized to be deleted in the role's "deleting" observer event.
        // Note: This requires the configured role model to be or to extend
        // the \Rawilk\LaravelBase\Models\Role model.
        $this->authorize(PermissionName::ROLES_DELETE);

        $ids = $this->selectedRowsQuery->pluck('id');

        $deleteCount = $this->roleModel::destroy($ids);

        $this->showDeleteAll = false;
        $this->deleting = null;

        $this->notify(Lang::choice('laravel-base::roles.alerts.bulk_deleted', $deleteCount, ['count' => $deleteCount]));
    }

    public function exportSelected(): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->isExporting = true;

        return (new RolesExport($this->hasSelection ? $this->selectedRowsQuery : $this->rowsQuery))
            ->usingColumns(array_merge(static::SELECTABLE_COLUMNS, ['permissions']))
            ->download('roles_' . time() . '.xlsx');
    }

    public function getRoleModelProperty(): Role
    {
        return app(Config::get('permission.models.role'));
    }

    public function getRowsQueryProperty()
    {
        $query = $this->roleModel::query()
            ->when($this->filters['search'], fn ($query, $search) => $query->modelSearch(['name', 'description'], $search))
            ->when($this->filters['created-min'], fn ($query, $date) => $query->where('created_at', '>=', $this->localizeMinDate($date)))
            ->when($this->filters['created-max'], fn ($query, $date) => $query->where('created_at', '<=', $this->localizeMaxDate($date)))
            ->when($this->filters['updated-min'], fn ($query, $date) => $query->where('updated_at', '>=', $this->localizeMinDate($date)))
            ->when($this->filters['updated-max'], fn ($query, $date) => $query->where('updated_at', '<=', $this->localizeMaxDate($date)))
            ->select($this->getColumnsToSelect());

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function selectPageRows(): void
    {
        $this->selected = $this->rows
            ->whereNotIn('name', RoleModel::protectedRoleNames())
            ->pluck('id')
            ->map(fn ($id) => (string) $id);
    }

    public function getSelectableRowCountProperty(): int
    {
        return $this->rows->whereNotIn('name', RoleModel::protectedRoleNames())
            ->count();
    }

    public function mount(): void
    {
        $this->hideableColumns = [
            'id' => __('laravel-base::messages.labels.model.id'),
            'name' => __('laravel-base::roles.labels.name'),
            'description' => __('laravel-base::roles.labels.description'),
            'created_at' => __('laravel-base::messages.labels.model.created_at'),
            'updated_at' => __('laravel-base::messages.labels.model.updated_at'),
        ];

        $this->hidden = ['id', 'created_at'];
    }

    public function render(): View
    {
        return view('laravel-base::livewire.roles.index.index', [
            'roles' => $this->rows,
        ])->layout(LaravelBase::adminViewLayout(), [
            'title' => __('laravel-base::roles.index.title'),
        ]);
    }

    protected function getColumnsToSelect(): array
    {
        if ($this->isExporting) {
            return static::SELECTABLE_COLUMNS;
        }

        return array_filter(static::SELECTABLE_COLUMNS, function ($column) {
            if ($column === 'id' || $column === 'name') {
                return true;
            }

            return ! $this->isHidden($column);
        });
    }
}
