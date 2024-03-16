<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'quiz_id';
    protected $table = 'quizzes';

    protected $fillable = [
        'syllabus_id',
        'quiz_title',
        'topic_id',
        'course_id',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }
}
