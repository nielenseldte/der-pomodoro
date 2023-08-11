<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBreak extends Model
{
    use HasFactory;
    /**
     * Means the break has been started
     * @var string
     */
    const STATUS_STARTED = 'started';
    /**
     * Means the break is ticking/busy running
     * @var string
     */
    const STATUS_TICKING = 'ticking';
    /**
     * Means the break is paused and not running, but not done
     * @var string
     */
    const STATUS_PAUSED = 'paused';
    /**
     * Means the break has successfuly ended and was not canceled, meaning it ticked to zero
     * @var string
     */
    const STATUS_ENDED = 'ended';
    /**
     * Means the user did not want to take the break and skipped it, it did not successfuly tick to 0
     * @var string
     */
    const STATUS_SKIPPED = 'skipped';

    //Cast the date fields to datetime objects
    public $casts = [
        'started_at' => 'datetime',
        'progressed_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Gives the break the user it belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Starts a new break and returns it
     *
     * Checks in this process weather the user is due a long break or not, using the mod operator and modding the count of the pomodoro (the number of the break) by the long break interval.
     *
     * @return UserBreak|null
     */
    public static function start(User $user = null)
    {
        //Get authenticated user
        if (!$user) {
            $user = Auth::user();
        }

        //If no user is logged in, return null
        if (!$user) return null;

        //Make sure user has settings, otherwise create some
        if (!$user->settings) {
            Settings::createSettings($user);
            $user->load('settings');
        }

        $latestBreak = UserBreak::where('user_id', $user->id)->orderBy('id', 'desc')->first(); //Get the latest break for the current user
        $pomodoroCount = 1; // set the pomodoro count to one

        //If there is a previous break, increment THAT pomodoro count by one meaning we overwrite the previously assigned one
        if ($latestBreak) {
            $pomodoroCount = $latestBreak->pomodoro_count + 1;
        }

        $isLongBreak = $pomodoroCount % $user->settings->long_break_interval == 0; //Check if the count is divisible by the user's long break interval meaning it is time for a long break. return true if so, false if not

        //Create a new break instance
        $newBreak = new UserBreak();
        $newBreak->user_id = $user->id;
        $newBreak->started_at = now(); //progressed at field is set to current time
        $newBreak->current_status = static::STATUS_STARTED;
        $newBreak->progressed_at = now(); //progressed at field is set to current time

        //Assign the pomodoro count and the break type
        $newBreak->pomodoro_count = $pomodoroCount;
        $newBreak->is_long_break = $isLongBreak;
        $newBreak->save();
        return $newBreak;
    }

    /**
     * This fucntion ends a break and starts the next focussession
     *
     * It is checked it the break has not ended, if not its status is set to ended and the completed at time is set to current time
     * The static method start() of FocusSession is then called and chained with pause so it does not start automatically ticking
     * @return static
     */
    public function end()
    {
        if ($this->current_status !== static::STATUS_ENDED) {
            $this->completed_at = now();
            $this->current_status = static::STATUS_ENDED;
            $this->save();
            //$this->user->endBreak();
            //When break ends lets start the next focus session
            FocusSession::start($this->user)->pause();

        }
        return $this;
    }

    /**
     * Skip is used the same as cancel() for a break, it skips the current break and goes straight to the next session which will be a focus session
     *
     * checks whether state is skipped, if state is not skipped it sets progressed at to now, but leaves completed at blank and sets the status to skipped
     * The next thing to do is to create a new focusSession and chain the pause method so it doesnt tick automatically
     * @return static
     */
    public function skip() {
        if ($this->current_status !== static::STATUS_SKIPPED) {
            $this->progressed_at = now();
            $this->current_status = static::STATUS_SKIPPED;
            $this->save();
            $user = Auth::user();
            FocusSession::start($user)->pause();

        }
        return $this;
    }

    /**
     * The pause method pauses the current session to be resumed at a later time
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
     * resume function checks first of all if a session is in a paused state, then it resumes the session
     *
     * Takes into account the time the session was paused by getting the difference between last progressed at and the current time
     * sets progressed at to now and status back to ticking
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
     * The tick method is in charge of checking how long the timer must be and calculating the runtime in seconds as well as terminating the timer when its time has elapsed
     *
     * First the is_long_break boolean is used to determine weather the user's long break length or short break length should be fetched from the database
     * the runtime is calculated by getting the difference between the started at and the current progressed at time
     * When the runtime in seconds is equal to the session length in seconds the end() fucntion is called
     * @return static
     */
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

        }
        $this->save();
        return $this;
    }

    /**
     * Countdown fucntion handles returning the time remaining on the break in string form
     *
     *
     * @return string|null
     */
    public function countdown()
    {

        $user = Auth::user();

        // Check if the user is authenticated and if the user settings are available
        if (!$user || !$user->settings) {
            return null;
        }

        // Determine and calculate session length in seconds
        if ($this->is_long_break) {
            $sessionLengthInSeconds = $user->settings->long_break_length * 60;
        } else {
            $sessionLengthInSeconds = $user->settings->short_break_length * 60;
        }
        $runtimeSeconds = $this->started_at->diffInSeconds($this->progressed_at);

        $remainingTimeSeconds = $sessionLengthInSeconds - $runtimeSeconds; // Calculate the remaining time in seconds by subtracting the runtime from the sessionLength to show the user

        $currentTime = Carbon::now()->setTime(0, 0, 0); // Create a new Carbon instance representing the current time and we set the time to 00:00:00
        $countdownTime = $currentTime->addSeconds($remainingTimeSeconds); // Add the remaining time to the currenttime to get the countdown time to show the user. this will add the time to 00:00:00
        $countdown = $countdownTime->format('i:s'); //time is formatted to minutes, and seconds. this is what is needed as no timer will ever be an hour long in pomodoro

        return $countdown;
    }

    /**
     * Just a fast way to access the value of the is_long_break boolean outside the model
     * @return bool
     */
    public function isLong() {

        return $this->is_long_break;

    }


    /**
     * This fucntion is used to modify the state of the break through the GUI buttons.
     *
     * The fucntion checks whether a break is currently ticking, and then it calls the function's pause() method to pause the break.
     * If not the fucntion checks of the break has a paused status, since this would mean the method resume() would have to be called to resume the break from the paused state
     * @return static
     */
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

    /**
     * Function to adjust button labels in GUI according to status of the focus session.
     * @return string
     */
    public function buttonLabel()
    {
         if ($this->current_status == UserBreak::STATUS_TICKING) {
            return __('Stop');
        }
        if ($this->current_status == UserBreak::STATUS_PAUSED) {
            return __('Start');
        }
        return __('Start');


    }
}



