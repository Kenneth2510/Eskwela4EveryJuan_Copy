<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizReferences extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'quiz_reference_id';
    protected $table = 'quiz_reference';

    protected $fillable = [
        'quiz_id',
        'course_id',
        'syllabus_id',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }
}
