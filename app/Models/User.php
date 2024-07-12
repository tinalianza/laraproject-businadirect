namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';

    protected $fillable = [
        'fname',
        'lname',
        'mname',
        'contact_no',
        'user_type',
        'qr_code',
        'emp_no',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_no', 'id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function login()
    {
        return $this->hasOne(Login::class);
    }
}
