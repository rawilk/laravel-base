<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('model');
            $table->string('import');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('processed_rows')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->dateTimestamps();
        });
    }
};
