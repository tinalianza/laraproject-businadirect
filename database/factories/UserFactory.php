namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'mname' => $this->faker->firstName,
            'contact_no' => $this->faker->phoneNumber,
            'user_type' => $this->faker->randomElement([1, 2, 3]),
            'emp_no' => $this->faker->numberBetween(1, 100),
        ];
    }
}
