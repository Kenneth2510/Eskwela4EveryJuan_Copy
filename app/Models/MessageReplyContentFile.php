<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageReplyContentFile extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'message_reply_content_file_id';
    protected $table = 'message_reply_content_file';

    protected $fillable = [
        'message_reply_id',
        'message_reply_content_id',
        'message_reply_content_file',
    ];


    public function messageContent()
    {
        return $this->belongsTo(MessageContent::class, 'message_content_id');
    }

    public function messageReply()
    {
        return $this->belongsTo(MessageReply::class, 'message_reply_id');
    }

    public function messageReplyContent()
    {
        return $this->belongsTo(MessageReplyContent::class, 'message_reply_content_id');
    }
}
