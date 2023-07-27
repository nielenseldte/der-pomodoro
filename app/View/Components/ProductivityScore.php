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
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $user = Auth::user();
        if ($user) {

            $this->productivityScore = $user->ProductivityScore();

        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.productivity-score');
    }
}
