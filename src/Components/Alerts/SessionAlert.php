<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Alerts;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Rawilk\LaravelBase\Components\BladeComponent;

final class SessionAlert extends BladeComponent
{
    public function __construct(public string $type = 'alert')
    {
    }

    public function message(): string
    {
        return (string) Arr::first($this->messages());
    }

    public function messages(): array
    {
        return (array) Session::get($this->type);
    }

    public function exists(): bool
    {
        return Session::has($this->type) && count($this->messages()) > 0;
    }
}
