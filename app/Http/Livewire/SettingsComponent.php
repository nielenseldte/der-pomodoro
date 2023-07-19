<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use Illuminate\Support\Str;

class SettingsComponent extends Component
{
    public $message = 'boo';
    public $settings;



    public function initializeSettings()
    {
        $user = Auth::user();
        if (!$user) return;
        $this->settings = $user->getSettings();
    }


    public function render()
    {
        $this->initializeSettings();
        return view('livewire.settings-component', [
            'settings' => $this->settings,
        ]);
    }
}
