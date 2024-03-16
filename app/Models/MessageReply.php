<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReply extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'message_reply_id';
    protected $table = 'message_reply';

    protected $fillable = [

        'message_content_id',
        'sender_user_type',
        'reply_user_type',
        'reply_user_email',
        'date_sent',
        'isRead',
        'date_read',
    ];


}
