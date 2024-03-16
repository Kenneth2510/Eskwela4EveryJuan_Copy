<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'syllabus_id';
    protected $table = 'syllabus';

    protected $fillable = [
       'course_id',
       'topic_id',
       'topic_title',
       'category',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id'); 
    }
}
