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
        Schema::table('assignments',function(Blueprint $table) {
            $table->dropColumn('type');
            $table->enum('type',['short_answer','choices','yes_no','multiple_choice','multiple_response'])->after('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments',function(Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
