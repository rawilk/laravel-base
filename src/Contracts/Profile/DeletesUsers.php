<?php

namespace Rawilk\LaravelBase\Contracts\Profile;

interface DeletesUsers
{
    public function delete($user): void;
}
