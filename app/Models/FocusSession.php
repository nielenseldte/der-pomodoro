<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FocusSession extends Model
{
    use HasFactory;

    const STATUS_STARTED = 'started';
    const STATUS_TICKING = 'ticking';
    const STATUS_PAUSED = 'pasued';


    public $casts = [
        'started_at' => 'datetime',
        'progressed_at' => 'datetime'
    ];

    /**
     * Starts a new session and returns it
     *
     * @return FocusSession|null
     */
    public static function start() {
        //Get logged in user
        $user = Auth::user();

        if (!$user) return null;

        //Make sure user has settings
        if (!$user->settings) return null;

        //TODO: Deal with complexity of calling start when another session is underway....

        $newFocusSession = new FocusSession();
        $newFocusSession->user_id = $user->id;
        $newFocusSession->progressed_at = now();
        $newFocusSession->next_break_length = 10;
        $newFocusSession->current_status = static::STATUS_STARTED;
        $newFocusSession->started_at = now();
        $newFocusSession->save();
        return $newFocusSession;

    }

    protected function end() {

    }

    public function pause()
    {

    }

    public function resume()
    {
    }

    public function cancel()
    {
    }

    public function tick()
    {
        if ($this->current_status !== static::STATUS_TICKING) $this->current_status = static::STATUS_TICKING;
        $this->progressed_at = now();
        $this->save();
        return $this;
    }

    public function countdown() {
        //Get settings
        $user = Auth::user();
        if (!$user) return null;
        if (!$user->settings) return null;

        $sessionLengthInSeconds = $user->settings->session_length * 60;

        //How long have we been running?
        $runtimeSeconds = $this->started_at->diffInSeconds($this->progressed_at);

        $uptimeSeconds = $sessionLengthInSeconds - $runtimeSeconds;

        //Now convert to display format
        return now()->setTime(0,0,0)->addSeconds($uptimeSeconds)->format('i:s');


    }




}
