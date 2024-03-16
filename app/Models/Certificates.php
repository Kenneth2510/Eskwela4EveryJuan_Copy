<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'certificate_id';
    protected $table = 'certificates';

    protected $fillable = [
        'reference_id',
        'user_type',
        'user_id',
        'course_id'
    ];
}
