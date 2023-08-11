<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * This is a focuss session (the core of the pomomdoro technique)
 */
class FocusSession extends Model
{
    use HasFactory;
    /**
     * Means the session has been initialised, this constant is not being used
     * @var string
     */
    const STATUS_STARTED = 'started';
    /**
     * Means the session is actively in a running/ticking state
     * @var string
     */
    const STATUS_TICKING = 'ticking';
    /**
     * Means the session is in a paused state and not ticking, but not done either
     * @var string
     */
    const STATUS_PAUSED = 'paused';
    /**
     * Means the sesion has ended by ticking to 0, and not being canceled by the user
     * @var string
     */
    const STATUS_ENDED = 'ended';
    /**
     * This means the session was canceled by the user and could not successfuly tick to 0
     * @var string
     */
    const STATUS_CANCELED = 'canceled';

    /**
     * The type casting for date and time fields.
     * @var array
     */
    public $casts = [
        'started_at' => 'datetime',
        'progressed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Gives this session an owner (user)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Starts a new session and returns it
     *
     * This is a static method that creates a new focus session
     * The method checks if a user has no settings and creates settings for the user with the createSettings() method
     * The new focus session is created
     *
     * @return FocusSession|null
     */
    public static function start(User $user = null)
    {
        //Get Autorised user
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) return null;

        //Make sure user has settings
        if (!$user->settings) {
            Settings::createSettings($user);
            $user->load('settings');
        }



        $newFocusSession = new FocusSession();
        $newFocusSession->user_id = $user->id;
        $newFocusSession->progressed_at = now(); //sets the last progressed at to current time
        $newFocusSession->next_break_length = $user->settings->short_break_length; //fetches the next break length of the user, which in this case os always a short break
        $newFocusSession->current_status = static::STATUS_TICKING; //status initialised to my ticking constant
        $newFocusSession->started_at = now(); //session start time is stored as current time
        $newFocusSession->session_length = $user->settings->session_length; //the length of the focus session is fetched according to the users desired settings
        $newFocusSession->save();
        return $newFocusSession;
    }

    /**
     * ends the current focussession and starts a user break.
     *
     * Does this by setting the completed_at db field to the current time and adjusting the status to ended
     * Then calls startBreak() method to start a break.
     * @return static
     */
    public function end()
    {
        if ($this->current_status !== static::STATUS_ENDED) {
            $this->completed_at = now();
            $this->current_status = static::STATUS_ENDED;
            $this->save();
            $this->user->startBreak();
        }
        return $this;
    }



    /**
     * The pause function stops the ticking of the session
     *
     * The progressed at is set to current time and status is set to pause, IF the status is not already paused
     * @return static
     */
    public function pause()
    {
        if ($this->current_status !== static::STATUS_PAUSED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_PAUSED;
            $this->save();
        }
        return $this;
    }


    /**
     * Resume method resumes your session after a pause period of time
     *
     * does this by checking if a session is paused and then calculating the time the session was paused in order not to lose time
     * The paused duration time is added to the started at time in order to maintain correct calculation of time remaining
     * The session status is set back to ticking
     * @return static
     */
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


    /**
     * The cancel method cancels/aborts a user's current focus session, rendering it incomplete
     *
     * If session is not already canceled or ended it sets progressed at to now but leaves completed at blank
     * A new focus session is called up and started but chained with pause() method so it doesnt start ticking automatically
     * @return static
     */
    public function cancel()
    {
        if ($this->current_status !== static::STATUS_CANCELED and $this->current_status !== static::STATUS_ENDED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_CANCELED;
            $this->save();
            $user = Auth::user();
            $this->start($user)->pause();
        }

        return $this;
    }

    /**
     *handles internally the timer logic to start the ticking, and ensure the session is stopped when the session time has elapsed
     *
     * Checked if current status us paused ended or canceled since then ticking is NOT required
     * if status is not ticking, but also none of the above checked that would have returend, status is set to ticking
     *
     * Progressed at is set to now() current time
     * session length is calculated by multiplying user session length (which is in minutes) by 60
     * @return static
     */
    public function tick()
    {
        $user = Auth::user();
        if (in_array($this->current_status, [static::STATUS_PAUSED, static::STATUS_ENDED, static::STATUS_CANCELED])) return $this;

        if ($this->current_status !== static::STATUS_TICKING) {
            $this->current_status = static::STATUS_TICKING;
        }

        $this->progressed_at = now();
        $sessionLengthInSeconds = $user->settings->session_length * 60;

        // Calculate the elapsed time in seconds
        $runtimeSeconds = $this->started_at->diffInSeconds($this->progressed_at);

        if ($runtimeSeconds >= $sessionLengthInSeconds) {
            $this->end();
            # code...
        }
        $this->save();
        return $this;
    }

    /**
     * Get the remaining time in the session as a countdown string which can be displayed to user.
     *
     * If no user is found OR no settings are found return null
     *
     * @return string|null The remaining time in the session as a formatted countdown (mm:ss) or null if user or settings are not available(user was not authed)
     */
    public function countdown()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated and if the user settings are available
        if (!$user || !$user->settings) {
            return null;
        }
        $sessionLengthInSeconds = $user->settings->session_length * 60;   // Calculate the session length in seconds

        $runtimeSeconds = $this->started_at->diffInSeconds($this->progressed_at); // Calculate the elapsed time in seconds using started at and the progressed at, it checks the difference between the two. The difference is NOT the remaining time, but the runtime of the timer in seconds

        $remainingTimeSeconds = $sessionLengthInSeconds - $runtimeSeconds; //now the remaining time (which the user wants to see) is calculated by subtracting the runtime from the session length

        $currentTime = Carbon::now()->setTime(0, 0, 0); // Create a new Carbon instance representing the current time and we set the time to 00:00:00

        $countdownTime = $currentTime->addSeconds($remainingTimeSeconds); // Add the remaining time to the currenttime to get the countdown time to show the user. this will add the time to 00:00:00

        $countdown = $countdownTime->format('i:s'); //time is formatted to minutes, and seconds. this is what is needed as no timer will ever be an hour long in pomodoro

        return $countdown;
    }



    /**
     * This fucntion is used to modify the state of the session through the GUI buttons.
     *
     * The fucntion checks whether a session is currently ticking, and then it calls the function's pause() method to pause the session.
     * If not the fucntion checks of the session has a paused status, since this would mean the method resume() would have to be called to resume the session from the paused state
     * @return static
     */
    public function toggle()
    {

        if ($this->current_status == FocusSession::STATUS_TICKING) {
            $this->pause();
            return $this;
        }

        if ($this->current_status == FocusSession::STATUS_PAUSED) {
            $this->resume();
            return $this;
        }
    }

    /**
     * Function to adjust button labels in GUI according to status of the focus session.
     * @return string
     */
    public function buttonLabel()
    {
        if ($this->current_status == FocusSession::STATUS_TICKING) {
            return __('Stop');
        }
        if ($this->current_status == FocusSession::STATUS_PAUSED) {
            return __('Start');
        }
        if ($this->current_status == FocusSession::STATUS_CANCELED) {
            return __('Start');
        }
        return __('Start');
    }
}
