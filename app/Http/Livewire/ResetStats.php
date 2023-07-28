<?php

namespace App\Http\Livewire;

use Log;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ResetStats extends Component
{
    public $reset_clicked = false;


    public function resetAll() {
        $this->reset_clicked = true;
        Log::debug('Reset clicked');
    }

    public function confirm() {
        $this->reset_clicked = false;

        $user = Auth::user();
        //if ($user)  $user->resetAll();
        return redirect('/stats');
    }

    public function cancel()
    {
        $this->reset_clicked = false;

    }


    public function render()
    {
        return view('livewire.reset-stats');
    }
}
