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
        }

        $this->settings = $this->makeChartSettings($user);
    }

    /**
     * Method used to build/pass the settings of the ChartJS chart in the component.
     *
     * This method takes a user and constructs the configuration settings for the ChartJS
     * to be displayed in the component. The settings include the chart type, data labels, dataset,
     * and visuals of the chart.
     * @param \App\Models\User $user The user which the chart settings are being created for.
     * @return array An associative array with the ChartJS configuration settings.
     */
    public function makeChartSettings(User $user)
    {
        $data = []; //initializing an empty array in which data will be stored

        //this is the default settings in JSON, i do not have customization options at this time
        $defaultConfigJson = '{"type":"bar","data":{"labels":[],"datasets":[{"label":"Number of Hours per day","data":[],"backgroundColor":"lime","borderWidth":1}]},"options":{"plugins":{"legend":{"display":true,"labels":{"font":{"size":14,"weight":"bold"},"color":"black"}}},"scales":{"y":{"beginAtZero":true,"grid":{"display":false},"ticks":{"color":"lime","font":{"weight":"bold"}}},"x":{"grid":{"display":false},"ticks":{"color":"lime","font":{"weight":"bold"}}}}}}';
        $data = json_decode($defaultConfigJson, true);// json is decoded into an associative array

        Arr::set($data, 'data.labels', $this->daysOfTheWeek); //x axis labels are being set to days of the week

        Arr::set($data, 'data.datasets.0.data', $this->hoursByDay); //y axis labels are being set to the hours by day
        return $data; // return the array containing data
    }

    /**
     * Render the dailyhourschart
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.daily-hours-chart');
    }
}
