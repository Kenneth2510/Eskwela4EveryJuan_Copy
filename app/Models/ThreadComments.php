<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadComments extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_comment_id';
    protected $table = 'thread_comments';

    protected $fillable = [
       'thread_comments_id',
       'thread_id',
       'user_id',
       'user_type',
       'thread_comment',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id'); 
    }

    
}
