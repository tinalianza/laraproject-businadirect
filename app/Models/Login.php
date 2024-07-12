namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;

    protected $table = 'login';

    protected $fillable = [
        'user_id',
        'email',
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class);
    }
}
