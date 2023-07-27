<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class dailyHoursChart extends Component
{

    public $daysOfTheWeek;
    public $hoursByDay;
    public $settings;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->daysOfTheWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $user = Auth::user();

        if ($user) {
            $this->hoursByDay = $user->hoursByDay($this->daysOfTheWeek);



            //dd($this->hoursByDay);
        }

        $this->settings = $this->makeChartSettings($user);

    }


    public function makeChartSettings(User $user)
    {
        $settings = [];
        $dataSets = [];
        $defaultConfigJson = '{"type":"bar","data":{"labels":[],"datasets":[{"label":"Number of Hours per day","data":[],"backgroundColor":"lime","borderWidth":1}]},"options":{"plugins":{"legend":{"display":true,"labels":{"font":{"size":14,"weight":"bold"},"color":"black"}}},"scales":{"y":{"beginAtZero":true,"grid":{"display":false},"ticks":{"color":"lime","font":{"weight":"bold"}}},"x":{"grid":{"display":false},"ticks":{"color":"lime","font":{"weight":"bold"}}}}}}';
        $settings = json_decode($defaultConfigJson,true);

        //Set X axis
        Arr::set($settings,'data.labels',$this->daysOfTheWeek);

        //Set data
        Arr::set($settings, 'data.datasets.0.data', $this->hoursByDay);



        return $settings;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.daily-hours-chart');
    }
}
