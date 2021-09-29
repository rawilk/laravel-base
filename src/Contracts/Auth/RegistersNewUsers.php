<?php

namespace Rawilk\LaravelBase\Contracts\Auth;

interface RegistersNewUsers
{
    public function create(array $input);
}
