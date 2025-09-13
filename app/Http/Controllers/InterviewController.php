<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\interviews_table;
use App\Models\questions_table;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function __construct(){ $this->middleware('auth'); }


    public function index()
{
    $user = Auth::user();

    // common query
    $query = interviews_table::with('questions')->latest();

    if ($user->isAdmin() || $user->isReviewer()) {
        // you could filter admin/reviewer differently here if needed
        $interviews = $query->paginate(10); // paginate instead of get()
    } else {
        // candidates see the same list (customize if needed)
        $interviews = $query->paginate(10);
    }

    return view('interviews.index', compact('interviews'));
}

    public function create(){ $this->authorizeCreate(); return view('interviews.create'); }

    public function store(Request $request) {
        $this->authorizeCreate();
        $data = $request->validate(['title'=>'required|string','description'=>'nullable|string']);
        $data['created_by'] = Auth::id();
        $interview = interviews_table::create($data);
        return redirect()->route('interviews.edit', $interview)->with('success','Interview created');
    }

     public function edit(interviews_table $interview) {
        $this->authorizeCreate();
        $interview->load('questions');
        return view('interviews.edit', compact('interview'));
    }

    protected function authorizeCreate(){
        $u = Auth::user();
        if(!($u->isAdmin() || $u->isReviewer())) abort(403);
    }

}
