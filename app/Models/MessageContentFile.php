<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageContentFile extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $primaryKey = 'message_content_file_id';
    protected $table = 'message_content_file';

    protected $fillable = [
        'message_id',
        'message_content_id',
        'message_content_file',
    ];


    public function messageContent()
    {
        return $this->belongsTo(MessageContent::class, 'message_content_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
