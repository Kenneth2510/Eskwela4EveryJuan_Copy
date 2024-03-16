<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerPostAssessmentProgress extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'learner_post_assessment_progress_id';
    protected $table = 'learner_post_assessment_progress';

    protected $fillable = [
        'learner_course_id',
        'learner_id',
        'course_id',
        'status',
        'start_period',
        'finish_period',
        'score',
        'max_duration',
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
}
