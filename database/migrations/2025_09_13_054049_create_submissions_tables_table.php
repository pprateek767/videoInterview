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
        Schema::create('submissions_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('interviews_tables')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions_tables')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->string('video_path'); // stored path in storage
            $table->integer('duration_sec')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions_tables');
    }
};
