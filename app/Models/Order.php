<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table ="orders";
    protected $fillable = [
        'uuid',
        'userId',
        'senderName',
        'itemName',
        'senderAddress',
        'senderCity',
        'senderPhone',
        'receiverName',
        'receiverAddress',
        'receiverCity',
        'receiverPhone',
        'cost',
        'shipper',
    ];
    public $timestamps = false;
}
