<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'drone_id',
        'battery_level'
    ];
}
