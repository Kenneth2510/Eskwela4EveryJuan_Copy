<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'activity_id';
    protected $table = 'activities';

    protected $fillable = [
        'syllabus_id',
        'activity_title',
        'topic_id',
        'course_id',
    ];

    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }
}
