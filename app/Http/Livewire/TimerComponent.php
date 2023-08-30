<?php

namespace App\Http\Livewire;

use App\Models\FocusSession;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TimerComponent extends Component
{
    //some default value initialisations
    public $ticker = '25:00';
    public $break = '05:00';
    public $onbreak = False;



    /**
     * The tick method is the heart of the program, and handles ticking the timer for whichever type of session we are on.
     *
     * First user is autenticated and then the onbreak variable is updated with the isOnBreak() method on the User model
     * If we indeed are on a break we fetch the latest break
     * if this was successful meaning there is a break to fetch we start ticking the break and counting down by chaining the
     * tick()->countdown() method on the user break model
     *
     * if none of this happens we fetch the current focussession
     * If the focussesion returns null we display the timer with the user's session length as a time, making the timer look ready for action and return
     * if a session ended we set the ticker value to be displayed to empty, since this will not be displayed and return
     * If not we tick and countdown the focussession with the relevan chained methods from the focus session model
     * @return void
     */
    private function tick()
    {

        $user = Auth::user();
        if (!$user) return;
        $this->onbreak = $user->isOnBreak();

        if ($this->onbreak == true) {
            $currentBreak = $user->getCurrentBreak();


            if ($currentBreak) {
                $this->break = $currentBreak->tick()->countdown();
            }
        }

        $currentFocusSession = $user->getCurrentFocusSession();
        if (!$currentFocusSession) {

            $this->ticker = $user->settings ? $user->settings->session_length . ':00' : '25:00';
            return;
        }
        if ($currentFocusSession->current_status == FocusSession::STATUS_ENDED) {

            $this->ticker = '';


            return;
        }
        $this->ticker = $currentFocusSession->tick()->countdown();
    }

    /**
     * Render the timer livewire component
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->tick();
        return view('livewire.timer-component');
    }
}
