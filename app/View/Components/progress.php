<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class progress extends Component
{
 public $progress = 0;
 public $dailyGoal = 2;

    public function __construct()
    {
        //
        $user = Auth::user();
        if ($user) {
                    # code...
                    if ($user->dailyProgress() <= 100){
                      $this->progress = round($user->dailyProgress());
                    }else {
                        $this->progress = 100;
                    }

                    $this->dailyGoal = $user->settings->daily_goal;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.progress');
    }
}
