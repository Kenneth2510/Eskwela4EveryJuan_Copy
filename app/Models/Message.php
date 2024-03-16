<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'message_id';
    protected $table = 'message';

    protected $fillable = [
        'message_content_id',
        'sender_user_type',
        'sender_user_email',
        'receiver_user_type',
        'receiver_user_email',
        'date_sent',
        'isRead',
        'date_read',
    ];


    public function messageContent()
    {
        return $this->belongsTo(MessageContent::class, 'message_content_id');
    }
}
