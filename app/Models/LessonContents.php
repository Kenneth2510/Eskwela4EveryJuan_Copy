<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonContents extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'lesson_content_id';
    protected $table = 'lesson_content';

    protected $fillable = [
        'lesson_content_id',
        'lesson_id',
        'lesson_content_title',
        'lesson_content',
        'lesson_content_order',
        'picture'
    ];

    public function syllabus()
    {
        return $this->belongsTo(Lessons::class, 'lesson_id');
    }
}
