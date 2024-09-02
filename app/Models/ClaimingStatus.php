<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimingStatus extends Model
{

    protected $table = 'claiming_status'; 

    protected $fillable = [
        'status_name',
    ];
}

