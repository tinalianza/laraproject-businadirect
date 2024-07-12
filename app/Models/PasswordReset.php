namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_reset';

    protected $fillable = [
        'login_id',
        'reset_token',
        'expiration',
    ];

    public function login()
    {
        return $this->belongsTo(Login::class);
    }
}
