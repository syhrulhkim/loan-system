<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'user_id',
        'payment_id',
        'payment_startDue',
        'payment_endDue',
        'date', 
        'amount_loan', 
        'amount_paid', 
        'date_paid', 
        'status',
    ];

}
