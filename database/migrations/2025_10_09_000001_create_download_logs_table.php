<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->string('url', 1000)->nullable();
            $table->string('name', 120);
            $table->string('email', 150);
            $table->string('role', 30);
            $table->string('purpose', 500);
            $table->string('ip', 64)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_logs');
    }
};
