<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RFID extends Model
{
    protected $table ="rfid";
    protected $fillable = [
        'userId',
        'ktp_no',
    ];
    public $timestamps = false;
}
