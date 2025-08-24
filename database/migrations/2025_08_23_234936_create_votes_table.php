<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('word_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->boolean('vote'); // true per correct, false per incorrect
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
