<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('photo_comments', function (Blueprint $table) {
            $table->id();
            $table->string('photo_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('photo_comments');
    }
};
