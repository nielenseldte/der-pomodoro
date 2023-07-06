<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\FocusSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StopTimerButton extends Component
{
    public ?FocusSession $focus_session;
    public $button_text = 'Start';

    private function setCurrentSession() {
        $user = Auth::user();
        if (!$user) return;
        $this->focus_session = $user->getCurrentFocusSession();
    }

    public function stopStart() {

        $this->setCurrentSession();
        if (!$this->focus_session) {
            $this->focus_session = FocusSession::start();
        }

        Log::debug("Im toggling " . $this->button_text);
        if ($this->button_text == __('Stop')) {

            $this->focus_session->pause();

            $this->button_text = __('Start');
        } else {
            $this->focus_session->resume();

            $this->button_text = __('Stop');
        }

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
