<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\LaravelBase\PasswordValidationRules;
use App\Models\User\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Rawilk\LaravelBase\Contracts\Auth\RegistersNewUsers;
use Rawilk\LaravelCasters\Support\Name;

class RegisterUserAction implements RegistersNewUsers
{
    use PasswordValidationRules;

    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')
            ],
            'password' => $this->passwordRules(needsConfirm: false),
        ])->validate();

        $name = Name::from($input['name']);

        return User::create([
            'first_name' => $name->first,
            'last_name' => $name->last,
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}
