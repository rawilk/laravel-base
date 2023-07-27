<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Support\Queries;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

class SortByRelationship
{
    private Model $model;

    private string $column = 'name';

    private string $direction = 'asc';

    private ?string $foreignKey = null;

    private string $primaryKey = 'id';

    public static function make(EloquentBuilder|QueryBuilder|Relation $query): self
    {
        return new static($query);
    }

    public function __construct(private EloquentBuilder|QueryBuilder|Relation $query)
    {
    }

    public function forRelationship(string $model): self
    {
        $this->model = app($model);

        return $this;
    }

    public function onColumn(string $column): self
    {
        $this->column = $column;

        return $this;
    }

    public function direction(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function usingForeignKey(string $foreignKey): self
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    public function usingPrimaryKey(string $primaryKey): self
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    public function apply(): void
    {
        $this->query->orderBy(function ($query) {
            $table = $this->model->getTable();
            $foreignKey = $this->foreignKey ?: Str::singular($table) . '_id';

            /** @var \Illuminate\Database\Query\Builder $query */
            $query->select($this->column)
                ->from($table)
                ->whereColumn($foreignKey, "{$table}.{$this->primaryKey}")
                ->limit(1);
        }, $this->direction);
    }
}
