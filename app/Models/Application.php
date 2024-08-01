<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'applicant_type',
        'employee_id',
        'email',
        'contact_no',
        'vehicle_type',
        'driver_license',
        'vehicle_model',
        'plate_number',
        'or_number',
        'cr_number',
        'expiration',
        'total_due',
    ];
}
