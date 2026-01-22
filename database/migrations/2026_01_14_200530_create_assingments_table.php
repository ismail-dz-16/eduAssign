<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::disableForeignKeyConstraints();
        // Schema::dropIfExists('assignments');
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type',['short_answer','yes_no','choices','multiple_choice','multiple_response','matching']);
            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('question');
            $table->date('estimated_date');
            $table->json('choices')->nullable();
            $table->longText('short_answer')->nullable();
            $table->longText('choice_answer')->nullable();
            $table->boolean('yes_no_answer')->nullable();
            $table->json('multiple_choices_answer')->nullable();
            $table->json('multiple_response_answer')->nullable();
            // $table->unsignedBigInteger('top_student')->nullable();
            // $table->foreign('top_student')->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
        // Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
