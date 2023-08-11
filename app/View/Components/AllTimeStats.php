<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AllTimeStats extends Component
{
    //variables to which to assign the values from the allTimeStats() fucntion array in the User model

    public $sessionsStarted;
    public $sessionsFinished;
    public $hoursFocused;
    public $allTimeStats;
    public $productivityScore;


    /**
     * We construct/create an instance of the allTimeStats class/component
     *
     * if a user is authenticated
     * values are assigned from the allTimeStats array that is passed from the allTimeStats() methods on user model
     * @return void
     */
    public function __construct()
    {
        //
        $user = Auth::user();
        if ($user) {
            $this->allTimeStats = $user->allTimeStats();
            $this->sessionsStarted = $this->allTimeStats['sessions_started'];
            $this->sessionsFinished = $this->allTimeStats['sessions_completed'];
            $this->hoursFocused = $this->allTimeStats['hours_focused'];
            $this->productivityScore = $this->allTimeStats['productivity_score'];
        }
        return;
    }

    /**
     * Render the alltimestats component
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.all-time-stats');
    }
}
