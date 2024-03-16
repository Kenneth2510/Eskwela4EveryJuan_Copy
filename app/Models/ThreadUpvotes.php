<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadUpvotes extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_upvote_id';
    protected $table = 'thread_upvotes';

    protected $fillable = [
       'thread_upvote_id',
       'thread_id',
       'base_upvote',
       'randomized_display_upvote',
       'last_randomized_datetime',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id'); 
    }
}
