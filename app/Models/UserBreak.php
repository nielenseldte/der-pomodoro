<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBreak extends Model
{
    use HasFactory;
    //Define constants for the possible status values
    const STATUS_STARTED = 'started';
    const STATUS_TICKING = 'ticking';
    const STATUS_PAUSED = 'pasued';
    const STATUS_ENDED = 'ended';

    //Cast the date fields to datetime objects
    public $casts = [
        'started_at' => 'datetime',
        'progressed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Starts a new session and returns it
     *
     * @return UserBreak|null
     */
    public static function start(User $user = null)
    {
        //Get logged in user
        if (!$user) {
            $user = Auth::user();
        }

        //If no user is logged in, return null
        if (!$user) return null;

        //Make sure user has settings
        if (!$user->settings) {
            Settings::createSettings($user);
            $user->load('settings');
        }
        //Get the latest break for the user
        $latestBreak = UserBreak::where('user_id', $user->id)->orderBy('id', 'desc')->first();

        //If there is no previous break, start from 1
        $pomodoroCount = 1;

        //If there is a previous break, increment the count by one
        if ($latestBreak) {
            $pomodoroCount = $latestBreak->pomodoro_count + 1;
        }

        //Check if the count is divisible by the user's long break interval
        $isLongBreak = $pomodoroCount % $user->settings->long_break_interval == 0;

        //Create a new break instance
        $newBreak = new UserBreak();

        //Assign the user id, current status, and start time to the new break
        $newBreak->user_id = $user->id;
        $newBreak->started_at = now();
        $newBreak->current_status = static::STATUS_STARTED;

        //Set the progressed_at field to the same as started_at for now
        $newBreak->progressed_at = now();

        //Assign the pomodoro count and the break type to the new break
        $newBreak->pomodoro_count = $pomodoroCount;
        $newBreak->is_long_break = $isLongBreak;

        //Save the new break to the database
        $newBreak->save();

        //Return the new break
        return $newBreak;
    }

    public function end()
    {
        if ($this->current_status !== static::STATUS_ENDED) {
            $this->completed_at = now();
            $this->current_status = static::STATUS_ENDED;
            $this->save();
            $this->user->endBreak();
            //When break ends lets start the next focus session
            FocusSession::start($this->user)->pause();
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

        if ($this->current_status === static::STATUS_PAUSED) {
            $pausedDuration = now()->diffInSeconds($this->progressed_at);
            $this->started_at = $this->started_at->addSeconds($pausedDuration);
            $this->progressed_at = now();
            $this->current_status = static::STATUS_TICKING;
            $this->save();
        }
        return $this;
    }

    public function tick()
    {
        $user = Auth::user();
        if (in_array($this->current_status, [static::STATUS_PAUSED, static::STATUS_ENDED])) return $this;

        if ($this->current_status !== static::STATUS_TICKING) {
            $this->current_status = static::STATUS_TICKING;
        }

        $this->progressed_at = now();
        if ($this->is_long_break) {
            $sessionLengthInSeconds = $user->settings->long_break_length * 60;

        } else {
            $sessionLengthInSeconds = $user->settings->short_break_length * 60;
        }


        // Calculate the elapsed time in seconds
        $runtimeSeconds = $this->started_at->diffInSeconds($this->progressed_at);

        if ($runtimeSeconds >= $sessionLengthInSeconds) {
            $this->end();
            # code...
        }
        $this->save();
        return $this;
    }

    public function countdown()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated and if the user settings are available
        if (!$user || !$user->settings) {
            return null;
        }

        // Calculate the session length in seconds
        if ($this->is_long_break) {
            $sessionLengthInSeconds = $user->settings->long_break_length * 60;
        } else {
            $sessionLengthInSeconds = $user->settings->short_break_length * 60;
        }

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

    public function isLong() {

        return $this->is_long_break;

    }


    public function toggle()
    {

        if ($this->current_status == UserBreak::STATUS_TICKING) {
            $this->pause();
            return $this;
        }

        if ($this->current_status == UserBreak::STATUS_PAUSED) {
            $this->resume();
            return $this;
        }
    }

    public function buttonLabel()
    {
        if ($this->current_status == UserBreak::STATUS_TICKING) {
            return __('Stop');
        }
        if ($this->current_status == UserBreak::STATUS_PAUSED) {
            return __('Start');
        }
    }
}



