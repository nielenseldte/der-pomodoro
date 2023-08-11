<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class WeeklyOverview extends Component
{
    //variables to which to assign the values from the weeklyStats() fucntion array in the User model
    public $sessionsStarted;
    public $sessionsFinished;
    public $hoursFocused;
    public $weeklyStats;
    public $productivityScore;


    /**
     * Constructs an instance of the weeklyoverview component/class
     *
     * authenticates the user and then
     * fills the variable values with the corresponding values in the array returned by weeklyStats() method
     * @return void
     */
    public function __construct()
    {
        $user = Auth::user();
        if ($user) {
            $this->weeklyStats = $user->weeklyStats();
            $this->sessionsStarted = $this->weeklyStats['sessions_started'];
            $this->sessionsFinished = $this->weeklyStats['sessions_completed'];
            $this->hoursFocused = $this->weeklyStats['hours_focused'];
            $this->productivityScore = $this->weeklyStats['productivity_score'];
        }
        return;
    }

    /**
     * Renders the weeklyoverview component
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.weekly-overview');
    }
}
