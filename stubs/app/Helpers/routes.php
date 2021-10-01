<?php

declare(strict_types=1);

if (! function_exists('homeRoute')) {
    function homeRoute(): string
    {
        if (! auth()->check()) {
            return '/';
        }

        return route('admin.dashboard');
    }
}
