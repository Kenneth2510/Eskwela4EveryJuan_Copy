<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerQuizOutputs extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_quiz_output_id';
    protected $table = 'learner_quiz_output';

    protected $fillable = [
        'learner_course_id',
        'learner_id',
        'course_id',
        'syllabus_id',
        'quiz_id',
        'quiz_content_id',
        'attempts',
        'answer',
        'isCorrect'
    ];

    public function learner_course()
    {
        return $this->belongsTo(LearnerCourse::class, 'learner_course_id');
    }

    public function learner()
    {
        return $this->belongsTo(Learner::class, 'learner_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }

    public function quiz_content()
    {
        return $this->belongsTo(QuizContents::class, 'quiz_content_id');
    }
}
