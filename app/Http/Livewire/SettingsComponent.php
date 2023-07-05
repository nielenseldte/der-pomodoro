<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class SettingsComponent extends Component
{
    public $message = 'boo';

    public function render()
    {
        return view('livewire.settings-component');
    }
}
