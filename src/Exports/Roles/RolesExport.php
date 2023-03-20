<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Exports\Roles;

use Carbon\CarbonInterface;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Rawilk\LaravelBase\Concerns\Exports\FormatsColumns;

class RolesExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    use Exportable;
    use FormatsColumns;

    protected array $columns = ['id', 'name', 'guard_name', 'description', 'created_at', 'updated_at'];

    public function __construct(protected $query)
    {
    }

    public function usingColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    /**
     * @param  \Spatie\Permission\Contracts\Role  $role
     */
    public function map($role): array
    {
        return collect($this->columns)
            ->map(function ($column) use ($role) {
                if ($column === 'permissions') {
                    $value = $role->getPermissionNames()->toJson();
                } else {
                    $value = $role->{$column};
                }

                if ($value instanceof CarbonInterface) {
                    return Date::dateTimeToExcel($value);
                }

                return $value;
            })
            ->toArray();
    }
}
