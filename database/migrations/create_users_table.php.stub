<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Rawilk\LaravelBase\Features;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();

            if (Features::enabled(Features::emailVerification())) {
                $table->dateTime('email_verified_at')->nullable();
            }

            $table->string('password');
            $table->string('timezone', 36)->default('UTC');

            if (Features::managesAvatars()) {
                $table->string('avatar_path', 2048)->nullable();
            }

            if (Features::canManageTwoFactorAuthentication()) {
                $table->text('two_factor_secret')->nullable();
                $table->text('two_factor_recovery_codes')->nullable();
            }

            $table->rememberToken();
            $table->dateTimestamps();
        });
    }
};
