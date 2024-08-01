<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $table = 'vehicle_types';

    protected $fillable = ['type'];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'vehicle_type_id');
    }
}
