<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCoverType extends Model
{
    use HasFactory;

    protected $table = 'insurance_cover_types';

    protected $fillable = [
        'cover_type',
        'status',
    ];

}
