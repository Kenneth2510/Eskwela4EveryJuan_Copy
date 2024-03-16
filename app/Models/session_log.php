<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class session_log extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'session_log_id';
    protected $table = 'session_logs';

    protected $fillable = [
        'session_user_id',
        'session_user_type',
        'session_in',
        'session_out',
        'time_difference'
    ];
}
