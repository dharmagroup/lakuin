<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table ="users";
    protected $fillable = [
        'userId',
        'email',
        'password',
        'userType',
        'fullname'
    ];
    public $timestamps = false;
}
