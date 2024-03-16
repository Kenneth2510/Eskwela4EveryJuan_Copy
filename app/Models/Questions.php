<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'question_id';
    protected $table = 'questions';

    protected $fillable = [
        'syllabus_id',
        'course_id',
        'question',
        'category'
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
