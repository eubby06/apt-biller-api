<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'unit',
    'total_heads',
    'rent_amount',
    'internet_plan',
    'move_in_date'
])]
class Tenant extends Model
{
    //
}
