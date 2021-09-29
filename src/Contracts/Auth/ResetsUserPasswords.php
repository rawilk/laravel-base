<?php

namespace Rawilk\LaravelBase\Contracts\Auth;

interface ResetsUserPasswords
{
    /**
     * Validate and reset the user's forgotten password.
     *
     * @param $user
     * @param array $input
     */
    public function reset($user, array $input);
}
