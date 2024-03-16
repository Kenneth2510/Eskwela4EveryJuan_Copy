<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerLessonProgress extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_lesson_progress_id';
    protected $table = 'learner_lesson_progress';

    protected $fillable = [
        'learner_course_id',
        'learner_id',
        'course_id',
        'syllabus_id',
        'lesson_id',
        'status',
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
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }
}
