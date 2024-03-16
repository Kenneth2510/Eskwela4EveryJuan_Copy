<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReplyContent extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'message_reply_content_id';
    protected $table = 'message_reply_content';

    protected $fillable = [
        'message_reply_id',
        'message_reply_content',
        'message_has_file',

    ];


    public function messageContent()
    {
        return $this->belongsTo(MessageContent::class, 'message_content_id');
    }

    public function messageReply()
    {
        return $this->belongsTo(MessageReply::class, 'message_reply_id');
    }
}
