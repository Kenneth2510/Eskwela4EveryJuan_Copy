<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageContent extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'message_content_id';
    protected $table = 'message_content';

    protected $fillable = [
        'message_subject',
        'message_content',
        'message_has_file',
        'date_updated'

    ];


}
