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


    public function stopStartBreak()
    {

        if (!$this->user_break) return;

        $this->user_break->toggle();

        $this->button_text = $this->user_break->buttonLabel();
    }



    public function cancel()
    {
        Log::debug("Im want to cancel");
        $this->setCurrentSession();
        if (!$this->focus_session) return;

        $this->focus_session->cancel();


    }


    public function render()
    {
        return view('livewire.stop-timer-button');
    }
}
