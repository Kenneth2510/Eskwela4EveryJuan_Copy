<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'lesson_id';
    protected $table = 'lessons';

    protected $fillable = [
        'syllabus_id',
        'lesson_title',
        'topic_id',
        'course_id',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }
}
