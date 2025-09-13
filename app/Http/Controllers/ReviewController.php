<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\reviews_table as Review;
use App\Models\submissions_table as Submission;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller {
    public function __construct(){ $this->middleware('auth'); }

    public function store(Request $r, Submission $submission) {
        $u = Auth::user();
        if(!($u->isAdmin() || $u->isReviewer())) abort(403);

        $data = $r->validate(['score'=>'required|integer|min:0|max:10','comment'=>'nullable|string']);
        $data['reviewer_id'] = $u->id;
        $data['submission_id'] = $submission->id;

        // For simplicity allow multiple reviews per reviewer+submission or update existing
        $review = Review::updateOrCreate(
            ['reviewer_id'=>$u->id,'submission_id'=>$submission->id],
            ['score'=>$data['score'],'comment'=>$data['comment']]
        );

        return back()->with('success','Review saved');
    }
}
