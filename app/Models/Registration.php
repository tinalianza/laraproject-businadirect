<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $primaryKey = 'id';

    protected $keyType = 'int';

    public $incrementing = true;

    protected $fillable = [
        'name',
        'applicant_type_id',
        'id_no',
        'email',
        'contact_no',
        'vehicle_type_id',
        'driver_license',
        'vehicle_model',
        'plate_number',
        'or_number',
        'cr_number',
        'expiration',
        'scanned_or_cr',
        'scanned_license',
        'scanned_id',
        'certificate',
        'total_due',
        'qr_code'
    ];

    public function applicantType()
    {
        return $this->belongsTo(ApplicantType::class, 'applicant_type_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
