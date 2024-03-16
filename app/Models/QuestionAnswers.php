<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswers extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'question_answer_id';
    protected $table = 'question_answer';

    protected $fillable = [
        'question_id',
        'answer',
        'isCorrect',
    ];

    public function question()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }

  
}
