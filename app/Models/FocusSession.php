<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FocusSession extends Model
{
    use HasFactory;

    const STATUS_STARTED = 'started';
    const STATUS_TICKING = 'ticking';
    const STATUS_PAUSED = 'pasued';
    const STATUS_ENDED = 'ended';
    const STATUS_CANCELED = 'canceled';


    public $casts = [
        'started_at' => 'datetime',
        'progressed_at' => 'datetime'
    ];

    /**
     * Starts a new session and returns it
     *
     * @return FocusSession|null
     */
    public static function start(User $user = null) {
        //Get logged in user
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) return null;

        //Make sure user has settings
        if (!$user->settings) {
            Settings::createSettings($user);
            $user->load('settings');
        }

        //TODO: Deal with complexity of calling start when another session is underway....

        $newFocusSession = new FocusSession();
        $newFocusSession->user_id = $user->id;
        $newFocusSession->progressed_at = now();
        $newFocusSession->next_break_length = $user->settings->short_break_length;
        $newFocusSession->current_status = static::STATUS_STARTED;
        $newFocusSession->started_at = now();
        $newFocusSession->save();
        return $newFocusSession;

    }

    protected function end() {
        if ($this->current_status !== static::STATUS_ENDED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_ENDED;
            $this->save();
        }
        return $this;

    }

    public function pause()
    {
        if ($this->current_status !== static::STATUS_PAUSED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_PAUSED;
            $this->save();
        }
        return $this;


    }

    public function resume()
    {
        if ($this->current_status == static::STATUS_PAUSED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_STARTED;
            $this->save();
        }
        return $this;

    }

    public function cancel()
    {
        if ($this->current_status !== static::STATUS_CANCELED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_CANCELED;
            $this->save();
        }

        return $this;

    }

    public function tick()
    {
        if (in_array($this->current_status, [static::STATUS_PAUSED, static::STATUS_ENDED, static::STATUS_CANCELED])) return $this;

        if ($this->current_status !== static::STATUS_TICKING) {
            $this->current_status = static::STATUS_TICKING;
        }
        $this->progressed_at = now();
        $this->save();
        return $this;
    }

    /**
     * Get the remaining time in the session as a countdown.
     *
     * @return string|null The remaining time in the session as a formatted countdown (mm:ss) or null if user or settings are not available.
     */
    public function countdown()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated and if the user settings are available
        if (!$user || !$user->settings) {
            return null;
        }

        // Calculate the session length in seconds
        $sessionLengthInSeconds = $user->settings->session_length * 60;

        // Calculate the elapsed time in seconds
        $runtimeSeconds = $this->started_at->diffInSeconds($this->progressed_at);

        // Calculate the remaining time in seconds
        $remainingTimeSeconds = $sessionLengthInSeconds - $runtimeSeconds;

        // Create a new Carbon instance representing the current time
        $currentTime = Carbon::now()->setTime(0, 0, 0);

        // Add the remaining seconds to the current time to get the countdown time
        $countdownTime = $currentTime->addSeconds($remainingTimeSeconds);

        // Format the countdown time as mm:ss
        $countdown = $countdownTime->format('i:s');

        return $countdown;
    }



}
