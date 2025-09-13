<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions_table extends Model
{
    use HasFactory;
    protected $fillable = [
        'interview_id',
        'question_text',
        'question_type',
        'options', // For multiple-choice questions, store options as JSON
        'time_limit', // Time limit in seconds
    ];

    public function interview() { return $this->belongsTo(interviews_table::class); }
    public function submissions() { return $this->hasMany(submissions_table::class); }
}
