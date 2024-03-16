<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityContents extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'activity_content_id';
    protected $table = 'activity_content';

    protected $fillable = [
        'activity_content_id',
        'activity_id',
        'activity_instructions',
        'total_score',
    ];

    public function activity()
    {
        return $this->belongsTo(Activities::class, 'activity_id');
    }
}
