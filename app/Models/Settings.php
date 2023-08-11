<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Settings class allow to store settings for each user and make the user experience unique
 */
class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_length',
        'short_break_length',
        'long_break_length',
        'long_break_interval',
        'daily_goal',
    ];
    /**
     * This settings belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }


    /**
     * Static method for creating user settings ensuring no user has no settings.
     *
     *
     * @param \App\Models\User|null $user the user for which to create the settings
     * @return Settings|null The created settings instance or null if no user was passed. Default values are as follows
     *                                  - focus session length is 25 minutes
     *                                  - Short break length is 5 minutes
     *                                  - Long break length is 15 minutes
     *                                  - long break interval is 4 focuss sessions
     *                                  - daily goal is 2 hours
     */
    public static function createSettings(User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        if (!$user) {
            return null;
        }
        $newSetting = new Settings();
        $newSetting->user_id = $user->id;
        $newSetting->session_length = 25;
        $newSetting->short_break_length = 5;
        $newSetting->long_break_length = 15;
        $newSetting->long_break_interval = 4;
        $newSetting->daily_goal = 2;
        $newSetting->save();
        return $newSetting;
    }
}
