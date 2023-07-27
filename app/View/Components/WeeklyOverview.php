<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class WeeklyOverview extends Component
{
    public $sessionsStarted;
    public $sessionsFinished;
    public $hoursFocused;
    public $weeklyStats;
    public $productivityScore;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
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
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.weekly-overview');
    }
}
