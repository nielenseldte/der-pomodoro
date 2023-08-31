<?php

namespace App\Models;

use App\Models\Settings;
use App\Models\FocusSession;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Boot method ensures we immediately assign settings to a user when a user is authorised/created
     * This prevents errors from occuring with accessing data that does not exist (as user settings does not exist until I create a focus session, which was a mistake from my side, this rectifies the mistake)
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::created(function (User $user) {
            Settings::createSettings($user);
        });

    }


    /**
     *
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];



    /**
     * Get the User's settings
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    /**
     * Get the user's focus sessions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function focusSessions()
    {
        return $this->hasMany(FocusSession::class);
    }
    /**
     * Get the user's breaks
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function breaks()
    {
        return $this->hasMany(UserBreak::class);
    }

    /**
     * Finds current session for user and fetches it
     *
     * @return FocusSession | null The current session or Null if no session is found
     */
    public function getCurrentFocusSession()
    {
        if (!$this->focusSessions) return null;
        return $this->focusSessions->sortByDesc('id')->first();
    }
    /**
     * Finds the latest user break and returns it
     * @return UserBreak | null returns null if no break is found
     */
    public function getCurrentBreak()
    {
        if (!$this->breaks) return null;
        return $this->breaks->sortByDesc('id')->first();
    }
    /**
     * Check if the user has an active break.
     * This fucntion returns True if the user has an active break and False if not
     * @return bool True if an active break exists, false otherwise.
     */
    public function hasActiveBreak()
    {
        $this->load('breaks');
        return ($this->breaks->whereIn('current_status', [UserBreak::STATUS_TICKING, UserBreak::STATUS_PAUSED])->first() !== null);
    }

    /**
     * uses the hasActiveBreak()  method to determine whether the user is on a break.
     * @return bool  True if the user is on a break, false otherwise.
     */
    public function isOnBreak()
    {
        return $this->hasActiveBreak();

    }
    /**
     * Start a new user break and pause it to prevent automatic ticking.
     *
     * This function initiates a new UserBreak and immediately pauses it to prevent it from
     * automatically ticking. The pauses() method is used for method chaining.
     * @return void
     */
    public function startBreak()
    {
        UserBreak::start($this)->pause();
        $this->load('breaks'); //ensure current state of break is up to date
    }

    /**
     * Calculates the user's daily progress
     *
     * Does this by retreiving the user's settings regarding their personalised daily goal, and then checking how many
     * hours the user has completed for the current day using Carbon class
     * After this the $hoursCompleted variable is divided by the daily goal and multiplied by a 100
     * to get the percentage progress for the progress bar
     * @return float
     */
    public function dailyProgress()
    {

        $dailyGoal = $this->settings->daily_goal;
        $hoursCompleted = ($this->focusSessions()->whereDate('completed_at', Carbon::today())->sum('session_length')) / 60;
        $progress = round(($hoursCompleted / $dailyGoal) * 100);
        if ($progress <= 100) {
            return $progress;
        }else {
            return 100;
        }
    }

    /**
     * Calculates the user's productivity score for the current week
     *
     * Uses Carbon to get the start and the end of the week, then gets all the sessions that were not completed
     * (has a canceled state) and all the sessions that were completed
     * Total started sessions is then calculated by adding $cancelledSessions and $completedSessions
     *
     * If the user has sessions (the $totalSessions variable is >0) the $productivity score is calculated as follows:
     * $completedSessions / $totalSession * 100 rounded to 2 decimals
     *
     * if there are no sessions zero is returned since we cannot do the calculation and risk dividing by zero
     * @return float|int
     */
    public function ProductivityScore()
    {

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();



        $cancelledSessions = $this->focusSessions()->where('current_status', 'canceled')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $totalSessions = $cancelledSessions + $completedSessions;
        if ($totalSessions > 0) {
            $productivity = round(($completedSessions / $totalSessions) * 100, 2);
            return $productivity;
        } else {
            $productivity = 0;
            return $productivity;
        }
    }

    /**
     * Calculates the all time stats of the user
     *
     * This function calculates various statistics based on the user's focus sessions, including the number of completed
     * sessions, sessions started, hours focused, and productivity score. The statistics are returned in an array form.
     *
     * The same check is performed as in ProductivityScore() function to ensure no division by zero
     *
     * @return array An array containing the calculated all-time statistics:
     *               - 'sessions_completed': Number of completed sessions.
     *               - 'sessions_started': Number of sessions started (the total number of sessions).
     *               - 'hours_focused': Total hours focused.
     *               - 'productivity_score': Productivity score based on completed vs. total sessions.
     */
    public function allTimeStats()
    {
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->count();
        $startedSessions = $this->focusSessions()->count();
        $hoursFocused = round($this->focusSessions()->whereNotNull('session_length')->where('current_status', 'ended')->sum('session_length')/60,2);
        $cancelledSessions = $this->focusSessions()->where('current_status', 'canceled')->count();
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->count();
        $totalSessions = $cancelledSessions + $completedSessions;
        if ($totalSessions > 0) {
            $productivityScore = round(($completedSessions / $totalSessions) * 100, 2);
        }else {
            $productivityScore = 0;
        }
        $allTimeStats = [
            'sessions_completed' => $completedSessions,
            'sessions_started' => $startedSessions,
            'hours_focused' => $hoursFocused,
            'productivity_score' => $productivityScore,
        ];

        return $allTimeStats;
    }

    /**
     * Get the user's statistics for the current week.
     *
     * The function calculates various statistics related to the user's focus sessions within the current week,
     * including the number of completed sessions, sessions started, hours focused, and productivity score.
     * @return array An array containing the calculated weekly focus session statistics:
     *               - 'sessions_completed': Number of completed sessions in the current week.
     *               - 'sessions_started': Number of sessions started in the current week.
     *               - 'hours_focused': Total hours focused in the current week.
     *               - 'productivity_score': Productivity score based on completed vs. total sessions in the current week.
     */

    public function weeklyStats()
    {

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $startedSessions = $this->focusSessions()->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $hoursFocused = round($this->focusSessions()->whereNotNull('session_length')->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->sum('session_length')/60,2);
        $cancelledSessions = $this->focusSessions()->where('current_status', 'canceled')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $totalSessions = $cancelledSessions + $completedSessions;
        if ($totalSessions > 0) {
            $productivityScore = round(($completedSessions / $totalSessions) * 100, 2);
        }else {
            $productivityScore = 0;
        }
        $weeklyStats = [
            'sessions_completed' => $completedSessions,
            'sessions_started' => $startedSessions,
            'hours_focused' => $hoursFocused,
            'productivity_score' => $productivityScore,
        ];

        return $weeklyStats;
    }

    /**
     * This function resets all the statistics for the current user
     *
     * This is done by deleting all the stored focussessions and breaks
     * @return void
     */
    public function resetAll()
    {
        $this->focusSessions()->delete();
        $this->breaks()->delete();
    }


    /**
     * Generate an array with the total hours focussed for each day of the week.
     *
     * The carbon class is used to determine the start and end of the week
     *
     *
     * @param array $labels An array of day labels for the x axis of my chart (e.g., ['Monday', 'Tuesday', ...]).
     * @return array An array with the total hours for each individual day of the week for the Y axis of my chart.
     */
    public function hoursByDay($labels)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $hoursByDay = $this->focusSessions()
            ->selectRaw('date(started_at) AS day_of_week, SUM(session_length)/60 AS total_hours') //divide by 60 to get hours since it it stored as minutes| I use selectRaw because it allows me to create my own custom SQL expression and allows me to return a complex/custom data structure i want for my chart
            ->whereNotNull('completed_at') // Only get completed sessions
            ->groupBy('day_of_week')
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->orderBy('day_of_week')
            ->get();
        $results = [];
        foreach ($hoursByDay as $hours) {
            $result = []; // this is the array that will hold the results by day
            $day = Carbon::createFromFormat('Y-m-d', $hours->day_of_week); //the data in the database is converted to a Carbon obj so I can work with it to access the specific DAY of the week
            $result['day_of_week'] = $day->englishDayOfWeek; //englishDayOfWeek changes it from a number to a word day
            $result['total_hours'] = $hours->total_hours;
            $results[] = $result; //add this day result to the array and go on with loop
        }

        $chartData = []; //the array that will hold my chart data
        $resultsByDay = collect($results); //collect converts the results array into a collection for easier data manipulation
        foreach ($labels as $label) {
            //Find the hours for EACH day label
            $totalForThisDay = $resultsByDay->where('day_of_week', $label)->first();
            $chartData[] = $totalForThisDay ? $totalForThisDay['total_hours']  : 0; // if a total is found for the day add the total to the day, otherwise add zero
        }

        return $chartData;
    }
}
