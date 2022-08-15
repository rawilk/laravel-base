<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Auth\RegisterResponse;
use Rawilk\LaravelBase\Contracts\Auth\RegistersNewUsers;
use Rawilk\LaravelBase\Contracts\Auth\RegisterViewResponse;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function register(RegistersNewUsers $action)
    {
        $user = $action->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        event(new Registered($user));

        $this->guard()->login($user);

        return app(RegisterResponse::class)->toResponse(request());
    }

    public function render(): View
    {
        return app(RegisterViewResponse::class)->toResponse(request());
    }

    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }
}
