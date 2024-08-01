<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantType extends Model
{
    use HasFactory;

    protected $table = 'applicant_types';

    protected $fillable = ['type'];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'applicant_type_id');
    }
}
