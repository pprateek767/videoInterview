<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class interviews_table extends Model
{

    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'scheduled_at',
        'created_by'

    ];

    public function creator()
    {
       return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(questions_table::class , 'interview_id');
    }

    public function submissions()
    {
        return $this->hasMany(submissions_table::class,'interview_id');
    }
}
