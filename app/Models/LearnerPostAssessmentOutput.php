<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerPostAssessmentOutput extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_post_assessment_output_id';
    protected $table = 'learner_post_assessment_output';

    protected $fillable = [
        'learner_course_id',
        'learner_id',
        'course_id',
        'question_id',
        'syllabus_id',
        'answer',
        'isCorrect',
        'attempt',
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

    public function question()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }
}
