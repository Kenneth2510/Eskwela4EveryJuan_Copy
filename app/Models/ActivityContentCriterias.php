<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityContentCriterias extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'activity_content_criteria_id';
    protected $table = 'activity_content_criteria';

    protected $fillable = [
        'activity_content_criteria_id',
        'activity_content_id',
        'criteria_title',
        'score',
    ];

    public function syllabus()
    {
        return $this->belongsTo(ActivityContents::class, 'activity_content_id');
    }
}
