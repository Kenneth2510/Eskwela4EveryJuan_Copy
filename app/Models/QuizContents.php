<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizContents extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'quiz_content_id';
    protected $table = 'quiz_content';

    protected $fillable = [
        'quiz_id',
        'syllabus_id',
        'course_id',
        'question_id'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function question()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }
}
