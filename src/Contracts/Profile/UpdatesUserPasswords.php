<?php

namespace Rawilk\LaravelBase\Contracts\Profile;

interface UpdatesUserPasswords
{
    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @param  bool  $updatingOtherUser Useful if updating a user's password from a user management page
     * @return void
     */
    public function update($user, array $input, bool $updatingOtherUser = false);
}
