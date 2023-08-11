<?php

namespace App\Http\Livewire;

use Log;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ResetStats extends Component
{
    /**
     * Initialise reset clicked variable to false, since it is not clicked at first
     * @var
     */
    public $reset_clicked = false;


    /**
     * Function sets the varaible rese_clicked to true so we can now display a confirm dialogue
     * @return void
     */
    public function resetAll() {
        $this->reset_clicked = true;
    }

    /**
     * This function calls the resetAll() method on the User model to delete all stats, then it refreshes the page with a redirect to the same page
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function confirm() {
        $this->reset_clicked = false;

        $user = Auth::user();
        if ($user) {
            $user->resetAll();
        }
        return redirect('/stats');
    }

    /**
     * Sets the reset_clicked variable back to false to abort a reset
     * @return void
     */
    public function cancel()
    {
        $this->reset_clicked = false;

    }


    /**
     * Renders the livewire stats component
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.reset-stats');
    }
}
