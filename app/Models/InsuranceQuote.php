<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceQuote extends Model
{
    use HasFactory;

    protected $table = 'insurance_quotes';
    protected $fillable = [
        'request_reference_number',
        'user_id',
        'vehicle_value_to_be_insured',
        'premium_proposed',
        'comment',
        'status',
        'insurance_cover_type',
        'insurance_period_requested',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
