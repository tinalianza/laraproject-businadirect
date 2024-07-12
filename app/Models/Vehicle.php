namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';

    protected $fillable = [
        'user_id',
        'model_color',
        'plate_no',
        'or_cr_no',
        'expiry_date',
        'driver_license_no',
        'copy_or_cr',
        'copy_driver_license',
        'copy_cor',
        'copy_school_id',
        'vehicle_type',
        'registration_no',
        'claiming_status',
        'vehicle_status',
        'apply_date',
        'issued_date',
        'sticker_expiry',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
