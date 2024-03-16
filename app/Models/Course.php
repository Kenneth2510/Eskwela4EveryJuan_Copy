<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'course_id';
    protected $table = 'course';

    protected $fillable = [
        'course_name',
        'course_code',
        'course_description',
        'course_status',
        'course_difficulty',
        'instructor_id'
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
}
