<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'short_answer',
                'choices',
                'yes_no'
            ]);
            $table->string('title', 50);
            $table->string('description', 200)->nullable();
            $table->string('question', 200);
            $table->string('short_answer', 200)->nullable();
            $table->json('choice_answer')->nullable();
            $table->boolean('yes_no_answer')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
