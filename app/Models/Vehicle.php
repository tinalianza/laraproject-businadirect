<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';

    protected $fillable = [
        'vehicle_owner_id',
        'model_color',
        'plate_no',
        'or_no',
        'cr_no',
        'expiry_date',
        'copy_or_cr',
        'copy_driver_license',
        'copy_cor',
        'copy_school_id',
        'vehicle_type_id',
    ];

    public function vehicleOwner()
    {
        return $this->belongsTo(VehicleOwner::class, 'vehicle_owner_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'vehicle_id');
    }
}
