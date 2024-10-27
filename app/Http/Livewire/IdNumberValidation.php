<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class IdNumberValidation extends Component
{
    public $id_no;
    public $errorMessage;

    public function updatedIdNo($value)
    {
        $this->validate([
            'id_no' => 'nullable|string',
        ]);

        $studentExists = DB::table('student')->where('std_no', $value)->exists();
        $employeeExists = DB::table('employee')->where('emp_no', $value)->exists();

        if (!$studentExists && !$employeeExists) {
            $this->errorMessage = 'ID number does not exist. Please contact the HR or Registrar office.';
        } else {
            $this->errorMessage = null; // Clear error if valid
        }
    }

    public function render()
    {
        return view('livewire.id-number-validation');
    }
}
