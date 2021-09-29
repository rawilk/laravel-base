<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Rawilk\LaravelBase\Contracts\Auth\LoginViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\RegisterViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\VerifyEmailViewResponse;

class SimpleViewResponse implements
    LoginViewResponse,
    RegisterViewResponse,
    VerifyEmailViewResponse
{
    /**
     * @param callable|string $view The name of the view or the callable used to generate the view.
     */
    public function __construct(protected $view)
    {
    }

    public function toResponse($request)
    {
        if (! is_callable($this->view) || is_string($this->view)) {
            return view($this->view, ['request' => $request]);
        }

        $response = call_user_func($this->view, $request);

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}
