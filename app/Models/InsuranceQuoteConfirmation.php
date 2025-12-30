<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceQuoteConfirmation extends Model
{
    use HasFactory;

    protected $table = 'insurance_quote_confirmations';

    protected $fillable = [
        'request_reference_number',
        'user_id',
        'premium_payable',
        'status',
    ];

}

