<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseGrading extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'course_grading_id';
    protected $table = 'course_grading';

    protected $fillable = [
        'course_id',
        'activity_percent',
        'quiz_percent',
        'pre_assessment_percent',
        'post_assessment_percent',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
