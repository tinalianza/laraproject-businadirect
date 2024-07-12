namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'emp_no',
        'fname',
        'lname',
        'mname',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'emp_no', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'emp_no', 'id');
    }
}
