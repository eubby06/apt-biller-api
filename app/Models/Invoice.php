<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'tenant_id',
    'prev_date',
    'prev_reading',
    'curr_date',
    'curr_reading',
    'electricity_rate',
    'electricity_amount',
    'price',
    'amount',
    'total_heads',
    'water_price',
    'water_amount',
    'rent_amount',
    'internet_amount',
    'total',
    'status',
])]
class Invoice extends Model {}
