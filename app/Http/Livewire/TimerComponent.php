<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TimerComponent extends Component
{
    public $time = '';



    public function render()
    {
        $this->time = now();
        return view('livewire.timer-component');
    }
}
