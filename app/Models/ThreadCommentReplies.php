<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadCommentReplies extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_comment_reply_id';
    protected $table = 'thread_comment_replies';

    protected $fillable = [
       'thread_comment_reply_id',
       'thread_id',
       'thread_comment_id',
       'user_id',
       'user_type',
       'thread_comment_reply',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id'); 
    }
    
    public function thread_comments()
    {
        return $this->belongsTo(ThreadComments::class, 'thread_comment_id'); 
    }
}
