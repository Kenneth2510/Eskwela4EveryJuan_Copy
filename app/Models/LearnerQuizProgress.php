<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerQuizProgress extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_quiz_progress_id';
    protected $table = 'learner_quiz_progress';

    protected $fillable = [
        'learner_course_id',
        'learner_id',
        'course_id',
        'syllabus_id',
        'quiz_id',
        'status',
        'attempt',
        'max_attempt',
        'score',
        'start_period',
        'finish_period',
    ];

    public function learner_course_id()
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

    public function lesson()
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }
}
