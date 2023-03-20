<?php

namespace Rawilk\LaravelBase\Contracts\Profile;

interface UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @return void
     */
    public function update($user, array $input);
}
