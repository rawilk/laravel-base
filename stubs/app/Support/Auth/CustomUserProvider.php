<?php

declare(strict_types=1);

namespace App\Support\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Eloquent\Builder;

final class CustomUserProvider extends EloquentUserProvider
{
    public function newModelQuery($model = null): Builder
    {
        $query = parent::newModelQuery($model);

        $query->withoutGlobalScopes();

        return $query;
    }
}
