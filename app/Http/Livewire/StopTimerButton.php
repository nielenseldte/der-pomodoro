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
    public $focus_session;
    public $user_break;
    public $button_text = 'Start';

    private function setCurrentSession()
    {
        $user = Auth::user();
        if (!$user) return;
        $this->user = $user;
        $this->focus_session = $user->getCurrentFocusSession();
        if ($user->isOnBreak()) {
            $this->user_break = $user->getCurrentBreak();
        } else {
            $this->user_break = null;
        }
    }

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


    public function stopStartBreak()
    {


        if (!$this->user_break) return;

        $this->user_break->toggle();

        $this->button_text = $this->user_break->buttonLabel();
    }



    public function cancel()
    {

        $this->setCurrentSession();
        if ($this->focus_session) {
            $this->focus_session->cancel();
            $this->button_text = $this->focus_session->buttonLabel();
            return;
        }
    }

    public function skipBreak()
    {
        $this->setCurrentSession();
        if ($this->user_break) {
            $this->user_break->skip();
            $this->button_text = $this->user_break->buttonLabel();
            return;
        }
    }

    public function syncButtonLabel() {
        Log::debug('Syncing button label. Current label is ' . $this->button_text);
        $this->setCurrentSession();
        if ($this->user_break) {
            Log::debug('There is a break lets get label from break');
            $this->button_text = $this->user_break->buttonLabel();
        } else {
            Log::debug('There is no break lets try to get focus session');
            if ($this->focus_session) {
                Log::debug('There is a focus session, lets get label from focus session');
                $this->button_text = $this->focus_session->buttonLabel();
            }
        }
        Log::debug('Set label to ' . $this->button_text);

    }

    public function render()
    {
        //$this->button_text = now();
        $this->syncButtonLabel();
        return view('livewire.stop-timer-button');
    }
}
