<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('photo_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('photo_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('reaction', ['like', 'dislike']);
            $table->timestamps();
            
            $table->unique(['photo_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('photo_reactions');
    }
};
