<?php

declare(strict_types=1);

namespace App\Actions\LaravelBase;

use Illuminate\Support\Facades\DB;
use Rawilk\LaravelBase\Contracts\Profile\DeletesUsers;
use Rawilk\LaravelBase\Features;

class DeleteUserAction implements DeletesUsers
{
    public function delete($user): void
    {
        DB::transaction(function () use ($user) {
            if (Features::managesAvatars()) {
                $user->deleteAvatar();
            }

            $user->delete();
        });
    }
}
