<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class progress extends Component
{

//default values are set to variables to be worked with
 public $progress = 0;
 public $dailyGoal = 2;


    /**
     * Constructs and instance of the progress bar component/class
     *
     * Progress value is updated by the user model's dailyProgress() method
     * daily goal value is fetched from the settings of the user
     * @return void
     */
    public function __construct()
    {
        //
        $user = Auth::user();
        if ($user) {
        $this->progress = $user->dailyProgress();
        $this->dailyGoal = $user->settings->daily_goal;
        }
        return;
    }

    /**
     * Render the progress bar component
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.progress');
    }
}
