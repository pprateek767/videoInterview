<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\interviews_table;
use App\Models\questions_table;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller {
    public function store(Request $r, interviews_table $interview) {
        $u = Auth::user();
        if(!($u->isAdmin() || $u->isReviewer())) abort(403);
        $data = $r->validate(['question_text'=>'required|string','order'=>'nullable|integer']);
        $data['order'] = $data['order'] ?? ($interview->questions()->count()+1);
        $interview->questions()->create($data);
        return back()->with('success','Question added');
    }

    public function destroy(interviews_table $interview, questions_table $question) {
        $u = Auth::user();
        if(!($u->isAdmin() || $u->isReviewer())) abort(403);
        $question->delete();
        return back()->with('success','Question removed');
    }
}
