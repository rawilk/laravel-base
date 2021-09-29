<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserProfileController
{
    public function show(Request $request): View
    {
        return view('pages.profile.show', [
            'user' => $request->user(),
        ]);
    }

    public function authentication(): View
    {
        return view('pages.profile.authentication');
    }
}
