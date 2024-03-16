<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'thread_id';
    protected $table = 'thread';

    protected $fillable = [
        'thread_id',
        'community_id',
        'user_id',
        'user_type',
    ];


    
}
