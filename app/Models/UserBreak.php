<?php

namespace App\Models;

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
        $newBreak->current_status = UserBreak::STATUS_STARTED;

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
}
