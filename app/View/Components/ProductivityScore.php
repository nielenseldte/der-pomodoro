<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ProductivityScore extends Component
{

    public $productivityScore;

    /**
     * Construct/create an instance of the productivity score component/class
     *
     * if a user is authenticated
     * the productivity score is calculated by the User model's productivityScore() method
     * @return void
     */
    public function __construct()
    {
        //
        $user = Auth::user();
        if ($user) {

            $this->productivityScore = $user->ProductivityScore();
        }
        return;
    }

    /**
     * renders the productivityscore component
     */
    public function render(): View|Closure|string
    {
        return view('components.productivity-score');
    }
}
