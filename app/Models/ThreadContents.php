<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadContents extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_content_id';
    protected $table = 'thread_contents';

    protected $fillable = [
       'thread_content_id',
       'thread_id',
       'thread_type',
       'thread_title',
       'thread_content',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id'); 
    }
}
