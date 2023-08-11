<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UserBreak;
use App\Models\FocusSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * This class is not named in the most descriptive way, it is actaully used to Start the timer as well as Stop it
 * All the logic is dealt with
 */
class StopTimerButton extends Component
{
    /**
     * Gets user associated with the instance if the user is available
     * @var User | null
     */
    public ?User $user;
    public $focus_session; // variable that will be used to work with focus session
    public $user_break; //variable that will be used to work with user breaks
    public $button_text = 'Start'; //default button text is set to start, this variable will be used to manipulate the text on the buttons in the GUI

    /**
     * This method determines what session is currently running, a focus session? or a break? and sets the relevant variables
     *
     * @return void sets variable values
     */
    private function setCurrentSession()
    {
        $user = Auth::user();
        if (!$user) return;
        $this->user = $user;
        $this->focus_session = $user->getCurrentFocusSession(); //method is called from User model to get the latest focus session, it will return null if no session is found

        //here the method isonbreak() is called from the User model that will check if the user is on a break
        if ($user->isOnBreak()) {
            $this->user_break = $user->getCurrentBreak(); //method getCurrentBreak() called from user model to get latest break
        } else {
            $this->user_break = null;
        }
    }

    /**
     * This function deals with starting/stopping focus sessions
     *
     * first the current session is fetched, and if it is a break, we return a different stopstart method especially for breaks
     * if not it checks if we are in a focus session and starts it with the static method start() on focussession model if we are not and sets the button labels.
     * Toggle() method is called to start or stop the focussession depending on state
     * button label is called as well to set button label
     * @return void
     */
    public function stopStart()
    {

        $this->setCurrentSession();
        if ($this->user->isOnBreak()) return $this->stopStartBreak();


        if (!$this->focus_session) {

            $this->focus_session = FocusSession::start();
            $this->button_text = $this->focus_session->buttonLabel();
            return;
        }

        $this->focus_session->toggle();

        $this->button_text = $this->focus_session->buttonLabel();
    }


    /**
     * This fucntion deals with stopping and starting breaks
     *
     * Checks if we are on a break and returns out the method if not
     * Otherwise the toggle() on user break model is called to start/stop the break accordingly
     * Button label meethod is also called to update the button text
     * @return void
     */
    public function stopStartBreak()
    {
        if (!$this->user_break) return;

        $this->user_break->toggle();

        $this->button_text = $this->user_break->buttonLabel();
    }

    /**
     * Cancel method deals with cancelling a focus session
     *
     * we get the current session and if its a focus session cancel() method is called and the button label is updated
     * @return void
     */
    public function cancel()
    {

        $this->setCurrentSession();
        if ($this->focus_session) {
            $this->focus_session->cancel();
            $this->button_text = $this->focus_session->buttonLabel();
            return;
        }
    }

    /**
     * This function is used to handle the skipping (essentially cancelling) of breaks
     *
     * sets the current session and if we are on a break we call the skip() method on the user break model and then the buton label method to update the text
     * @return void
     */
    public function skipBreak()
    {
        $this->setCurrentSession();
        if ($this->user_break) {
            $this->user_break->skip();
            $this->button_text = $this->user_break->buttonLabel();
            return;
        }
    }

    /**
     * The syncButtonLabel() fucntion deals with the issue of the button label not refreshing after a session is completed and setting to the correct value
     *
     * this method will be called on the render of the livewire component
     *
     * sets current session and then if we are on a break we call the user break model's buttonlabel method
     * if we are not on a break we check for focus session and we call the focus session model's buttonlabel method
     * @return void
     */
    public function syncButtonLabel() {

        $this->setCurrentSession();
        if ($this->user_break) {
            $this->button_text = $this->user_break->buttonLabel();
        } else {
            if ($this->focus_session) {
                $this->button_text = $this->focus_session->buttonLabel();
            }
        }

    }

    /**
     * Renders the timer stop start button livewire componenet and calls the syncbuttonLabel method
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->syncButtonLabel();
        return view('livewire.stop-timer-button');
    }
}
