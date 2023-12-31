<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;
use App\Models\Settings;
use Illuminate\Support\Str;

class SettingsComponent extends Component
{

    /**
     *
     * @var Settings
     */
    public Settings $settings;


    /**
     * Set my verification rules for the input of user settings
     * @var array
     */
    public $rules = [
            'settings.session_length' => 'required|numeric|min:15|max:50',
            'settings.short_break_length' => 'required|numeric|min:3|max:15',
            'settings.long_break_length' => 'required|numeric|min:10|max:25',
            'settings.long_break_interval' => 'required|numeric|min:3|max:6',
            'settings.daily_goal' => 'required|numeric|min:1|max:18',
    ];

    /**
     * Mount checks if the user is authenticateed and then if it is, sets the settings to the users settings from the database
     * @return void
     */
    public function mount()
    {
        $user = Auth::user();
        if (!$user) return;

        $this->settings = $user->settings;
    }
    /**
     * Defining my custom error messages that wiill display if rules are violated
     * @return array<string>
     */
    public function messages()
    {
        return [

            'settings.session_length.min' => 'The Focus Length must be between 15 and 50 minutes.',
            'settings.session_length.max' => 'The Focus Length must be between 15 and 50 minutes.',
            'settings.short_break_length.min' => 'The short break length must be between 3 and 15 minutes.',
            'settings.short_break_length.max' => 'The short break length must be between 3 and 15 minutes.',
            'settings.long_break_length.min' => 'The long break must be between 10 and 25 minutes.',
            'settings.long_break_length.max' => 'The long break must be between 10 and 25 minutes.',
            'settings.long_break_interval.min' => 'The long break inteval must be every 3 to 6 focus sessions.',
            'settings.long_break_interval.max' => 'The long break inteval must be every 3 to 6 focus sessions.',
            'settings.daily_goal.min' => 'The daily goal must be between 1 and 18 hours.',
            'settings.daily_goal.max' => 'The daily goal must be between 1 and 18 hours.',
        ];
    }


    /**
     * Reset settings to their default values
     * @return void
     */
    public function resetToDefault() {
        $this->settings->session_length = 25;
        $this->settings->short_break_length = 5;
        $this->settings->long_break_length = 15;
        $this->settings->long_break_interval = 4;
        $this->settings->daily_goal = 2;

    }

    /**
     * function saves validated settings after modification
     * @return void
     */
    public function save()
    {
        $this->validate(); //validated against my defined rules

        $this->settings->save();

        session()->flash('message', 'Your Settings have been successfully updated!');

    }



    /**
     * Render the livewire settings component
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.settings-component');
    }
}
