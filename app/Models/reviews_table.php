<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reviews_table extends Model
{
    use HasFactory;
    protected $fillable = [
        'submission_id',
        'reviewer_id',
        'comment',
       'score',
    ];

     public function reviewer() { return $this->belongsTo(User::class,'reviewer_id'); }
    public function submission() { return $this->belongsTo(submissions_table::class); }
}
