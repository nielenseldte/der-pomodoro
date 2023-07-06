<?php

namespace App\Http\Livewire;

use App\Models\FocusSession;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TimerComponent extends Component
{
    public $ticker = '25:00';


    public function updateSettings() {
        Log::debug('Boo');
    }

    private function tick() {

        $user = Auth::user();
        if (!$user) return;

        $currentFocusSession = $user->getCurrentFocusSession();
        if (!$currentFocusSession) {
            $this->ticker = $user->settings->session_length . ':00';
            return;
        }
        if ($currentFocusSession->current_status == FocusSession::STATUS_ENDED) {

            $this->ticker = 'ended';
            return;
        }
        $this->ticker = $currentFocusSession->tick()->countdown();

    }

    public function render()
    {
        $this->tick();
        return view('livewire.timer-component');
    }
}
