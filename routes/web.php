<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\ReviewController;

Route::get('/', function(){ return redirect()->route('interviews.index'); });

Route::middleware(['auth'])->group(function(){
    Route::get('/interviews', [InterviewController::class,'index'])->name('interviews.index');

    // Admin/Reviewer: create
    Route::get('/interviews/create', [InterviewController::class,'create'])->name('interviews.create');
    Route::post('/interviews', [InterviewController::class,'store'])->name('interviews.store');
    Route::get('/interviews/{interview}/edit', [InterviewController::class,'edit'])->name('interviews.edit');

    // Questions
    Route::post('/interviews/{interview}/questions', [QuestionController::class,'store'])->name('questions.store');
    Route::delete('/interviews/{interview}/questions/{question}', [QuestionController::class,'destroy'])->name('questions.destroy');

    // Candidate views an interview and can upload per-question answer
    Route::get('/candidate/interview/{interview}', [SubmissionController::class,'showInterview'])->name('candidate.interview');

    // Upload a response for a question
    Route::post('/interview/{interview}/question/{question}/submit', [SubmissionController::class,'store'])->name('submissions.store');

    // Reviewer: list submissions and review
    Route::get('/interviews/{interview}/submissions', [SubmissionController::class,'listForInterview'])->name('submissions.list');
    Route::post('/submissions/{submission}/review', [ReviewController::class,'store'])->name('reviews.store');
});

require __DIR__.'/auth.php'; // if using Breeze
