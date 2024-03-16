<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerActivityOutput extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_activity_output_id';
    protected $table = 'learner_activity_output';

    protected $fillable = [
        'learner_activity_output_id',
        'learner_course_id',
        'syllabus_id',
        'activity_id',
        'activity_content_id',
        'course_id',
        'answer',
        'total_score',
        'remarks',
        'max_attempt',
        'attempt',
        'mark',
    ];

    public function learner_course_id()
    {
        return $this->belongsTo(LearnerCourse::class, 'learner_course_id');
    }

    public function syllabus_id()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }

    public function activity_id()
    {
        return $this->belongsTo(Activities::class, 'activity_id');
    }

    public function activity_content_id()
    {
        return $this->belongsTo(ActivityContents::class, 'activity_content_id');
    }

    public function course_id()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
