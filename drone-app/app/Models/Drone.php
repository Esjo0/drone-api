<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Medication;

class Drone extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'serial_number',
        'model',
        'weight_limit',
        'battery_capacity',
        'state'
    ];

    /**
     * returns this drones medication
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medications()
    {
        return $this->hasMany('Medication', 'drone_id', 'id');
    }
}
