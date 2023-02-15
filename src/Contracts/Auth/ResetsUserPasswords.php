<?php

namespace Rawilk\LaravelBase\Contracts\Auth;

interface ResetsUserPasswords
{
    /**
     * Validate and reset the user's forgotten password.
     */
    public function reset($user, array $input);
}
