<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student';

    protected $primaryKey = 'id_no';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id_no', 
        'name', 
        'program', 
        'year_level'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'id_no', 'id_no');
    }
}
