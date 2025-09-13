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
        Schema::create('questions_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews_tables')->onDelete('cascade');
            $table->text('question_text');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions_tables');
    }
};
