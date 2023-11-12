<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'loan';

    protected $fillable = [
        'user_id',
        'loan_id',
        'start_date', 
        'end_date', 
        'months', 
        'interest', 
        'amount_loan', 
        'amount_loan_interest', 
        'amount_paid', 
        'date_paid', 
        'status', 
    ];

}
