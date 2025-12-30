<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceQuoteReply extends Model
{
    use HasFactory;

    protected $table = 'insurance_quote_reply';

    protected $fillable = [
        'request_reference_number',
        'user_id',
        'vehicle_value_to_be_insured',
        'premium_proposed',
        'comment',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

