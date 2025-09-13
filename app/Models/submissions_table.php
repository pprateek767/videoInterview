<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class submissions_table extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'question_id',
        'video_path',
        'candidate_id',
        'duration_sec',
    ];

    public function candidate() { return $this->belongsTo(User::class,'candidate_id'); }
    public function interview() { return $this->belongsTo(interviews_table::class,'id'); }
    public function question() { return $this->belongsTo(questions_table::class,'question_id'); }
    public function reviews() { return $this->hasMany(reviews_table::class , 'reviewer_id'); }
    // Each submission has ONE review
    public function review()
    {
        return $this->hasOne(reviews_table::class, 'submission_id');
    }

}
