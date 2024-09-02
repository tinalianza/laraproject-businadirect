<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction'; 

    protected $fillable = [
        'vehicle_id',
        'transac_type',
        'amount_payable',
        'reference_no',
        'emp_id',
        'registration_no',
        'claiming_status_id',
        'vehicle_status',
        'apply_date',
        'issued_date',
        'sticker_expiry',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    public function claimingStatus()
    {
        return $this->belongsTo(ClaimingStatus::class, 'claiming_status_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
