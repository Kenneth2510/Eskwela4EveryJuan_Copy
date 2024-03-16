<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Learner extends Authenticatable
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'learner_id';
    protected $table = 'learner';

    protected $fillable = [
        'learner_username',
        'password',
        'learner_security_code',
        'learner_fname',
        'learner_lname',
        'learner_bday',
        'learner_gender',
        'learner_contactno',
        'learner_email',
        'status',
        'profile_picture',
    ];
}
