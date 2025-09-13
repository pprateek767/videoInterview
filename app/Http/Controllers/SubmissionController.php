<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\submissions_table;
use App\Models\interviews_table;
use App\Models\questions_table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubmissionController extends Controller {
    public function __construct(){ $this->middleware('auth'); }

    // Candidate sees interview and questions
    public function showInterview(interviews_table $interview) {
        $interview->load('questions');
        return view('candidate.interview', compact('interview'));
    }

    // Accept uploaded video
    public function store(Request $request, interviews_table $interview, questions_table $question) {
       $userId = Auth::id();

        // 1. Check if user already submitted this question
        $existing = submissions_table::where('interview_id', $interview->id)
            ->where('question_id', $question->id)
            ->where('candidate_id', $userId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted an answer for this question.'
            ], 422); // 422 = Unprocessable Entity
        }

        // 2. Validate uploaded video
        $request->validate([
            'video' => 'required|file|mimetypes:video/webm,video/mp4|max:51200', // max ~50MB
            'duration_sec' => 'required|integer|min:1',
        ]);

        // 3. Save uploaded video
        $path = $request->file('video')->store("submissions/{$userId}", 'public');

        // 4. Create submission record
        $submission = submissions_table::create([
            'interview_id' => $interview->id,
            'question_id' => $question->id,
            'candidate_id'     => $userId,
            'video_path'  => $path,
            'duration'    => $request->duration_sec,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Answer submitted successfully',
            'submission_id' => $submission->id,
        ]);
    }

    // For reviewer/admin to list submissions for an interview or question
    public function listForInterview(interviews_table $interview) {
        $this->authorizeReview();
        $submissions = $interview->submissions()->with(['question','candidate','reviews'])->paginate(5);
        return view('reviews.submissions', compact('interview','submissions'));
    }

    protected function authorizeReview(){
        $u = Auth::user();
        if(!($u->isAdmin() || $u->isReviewer())) abort(403);
    }
}
