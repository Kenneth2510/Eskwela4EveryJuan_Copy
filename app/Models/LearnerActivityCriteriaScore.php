<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnerActivityCriteriaScore extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_activity_criteria_score_id';
    protected $table = 'learner_activity_criteria_score';

    protected $fillable = [
        'learner_activity_criteria_score_id',
        'learner_activity_output_id',
        'activity_content_criteria_id',
        'activity_content_id',
        'score',
        'attempt'
    ];

    public function activity_content_criteria()
    {
        return $this->belongsTo(ActivityContentCriterias::class, 'activity_content_criteria_id');
    }

    
    public function activity_content()
    {
        return $this->belongsTo(ActivityContents::class, 'activity_content_id');
    }
}
