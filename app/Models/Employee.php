<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $primaryKey = 'id_no';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['id_no', 'name', 'department', 'position'];

    /**
     * Get the registrations for the employee.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'id_no', 'id_no');
    }
}
