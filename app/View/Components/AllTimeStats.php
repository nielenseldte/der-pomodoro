<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AllTimeStats extends Component
{
    public $sessionsStarted;
    public $sessionsFinished;
    public $hoursFocused;
    public $allTimeStats;
    public $productivityScore;

    /**
     * Create a new component instance.
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
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.all-time-stats');
    }
}
