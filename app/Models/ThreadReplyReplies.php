<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadReplyReplies extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_reply_reply_id';
    protected $table = 'thread_reply_replies';

    protected $fillable = [
       'thread_reply_reply_id',
       'thread_id',
       'thread_comment_id',
       'thread_comment_reply_id',
       'user_id',
       'user_type',
       'thread_reply_reply',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id'); 
    }
    
    public function thread_comments()
    {
        return $this->belongsTo(ThreadComments::class, 'thread_comment_id'); 
    }

    public function thread_comment_replies()
    {
        return $this->belongsTo(ThreadCommentReplies::class, 'thread_comment_reply_id');  
    }
}
