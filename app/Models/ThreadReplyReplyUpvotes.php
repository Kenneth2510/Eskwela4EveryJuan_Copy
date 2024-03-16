<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadReplyReplyUpvotes extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_reply_reply_upvote_id';
    protected $table = 'thread_reply_reply_upvotes';

    protected $fillable = [
       'thread_reply_reply_upvote_id',
       'thread_id',
       'thread_comment_id',
       'thread_comment_reply_id',
       'thread_reply_reply_id',
       'base_upvote',
       'randomized_display_upvote',
       'last_randomized_datetime',
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

    public function thread_reply_replies()
    {
        return $this->belongsTo(ThreadReplyReplies::class, 'thread_reply_reply_id');  
    }
}
