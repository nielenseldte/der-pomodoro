<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UserBreak;
use App\Models\FocusSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StopTimerButton extends Component
{
    public ?User $user;
    public ?FocusSession $focus_session;
    public ?UserBreak $user_break;
    public $button_text = 'Start';

    private function setCurrentSession() {
        $user = Auth::user();
        if (!$user) return;
        $this->user = $user;
        $this->focus_session = $user->getCurrentFocusSession();
        if ($user->isOnBreak()) {
            $this->user_break = $user->getCurrentBreak();
        }
    }

    public function stopStart() {

        Log::debug('Pudhrf@');

        $this->setCurrentSession();
        if ($this->user->isOnBreak()) return $this->stopStartBreak();


        if (!$this->focus_session) {
            Log::debug('Make new');
            $this->focus_session = FocusSession::start();
            $this->button_text = $this->focus_session->buttonLabel();
            return;
        }
        Log::debug('Toggling now');
        $this->focus_session->toggle();
        Log::debug('Setting text');

        $this->button_text = $this->focus_session->buttonLabel();


    }


    public function stopStartBreak()
    {

        Log::debug('stop start break');
        if (!$this->user_break) return;

        $this->user_break->toggle();

        $this->button_text = $this->user_break->buttonLabel();
    }



    public function cancel()
    {

        $this->setCurrentSession();
        if ($this->focus_session) {
            $this->focus_session->cancel();
            $this->focus_session->buttonLabel();
            return;

        }




    }


    public function render()
    {
        return view('livewire.stop-timer-button');
    }
}
