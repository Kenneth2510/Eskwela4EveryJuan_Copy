<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{

    protected $connection = 'mysql';
    protected $primaryKey = 'admin_id';
    protected $table = 'admin';

    use HasFactory;

    protected $fillable = [
        'admin_username',
        'admin_codename',
        'role',
        'email',
        'password',
    ];

    // public function getAuthPassword() {
    //     return $this->admin_password;
    // }
}
