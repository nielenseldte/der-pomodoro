<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class dailyHoursChart extends Component
{

    public $daysOfTheWeek;
    public $hoursByDay;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $user = Auth::user();

        if ($user) {
            $this->hoursByDay = $user->hoursByDay();
            $this->daysOfTheWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            //dd($this->hoursByDay);
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.daily-hours-chart');
    }
}
