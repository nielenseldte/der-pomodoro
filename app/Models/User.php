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
     * The attributes that are mass assignable.
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




    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    public function focusSessions()
    {
        return $this->hasMany(FocusSession::class);
    }

    public function breaks()
    {
        return $this->hasMany(UserBreak::class);
    }

    /**
     * Finds current session for user
     *
     * @return FocusSession | null
     */
    public function getCurrentFocusSession()
    {
        if (!$this->focusSessions) return null;
        return $this->focusSessions->sortByDesc('id')->first();
    }

    public function getCurrentBreak()
    {
        if (!$this->breaks) return null;
        return $this->breaks->sortByDesc('id')->first();
    }

    public function hasActiveBreak()
    {
        $this->load('breaks');
        return ($this->breaks->whereIn('current_status', [UserBreak::STATUS_TICKING, UserBreak::STATUS_PAUSED])->first() !== null);
    }

    public function isOnBreak()
    {
        return $this->hasActiveBreak();
        //return session()->get('break', false);
    }

    public function startBreak()
    {
        //session()->put('break', true);
        UserBreak::start($this)->pause();
        $this->load('breaks');
    }

    public function dailyProgress()
    {

        $dailyGoal = $this->settings->daily_goal;
        $hoursCompleted = ($this->focusSessions()->whereDate('completed_at', Carbon::today())->sum('session_length')) / 60;
        $progress = ($hoursCompleted / $dailyGoal) * 100;
        return $progress;
    }

    public function ProductivityScore()
    {

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();


        $cancelledSessions = $this->focusSessions()->where('current_status', 'canceled')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $totalSessions = $cancelledSessions + $completedSessions;
        $productivity = round(($completedSessions / $totalSessions) * 100, 2);
        return $productivity;
    }

    public function allTimeStats() {
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->count();
        $startedSessions = $this->focusSessions()->count();
        $hoursFocused = $this->focusSessions()->whereNotNull('session_length')->sum('session_length');
        $cancelledSessions = $this->focusSessions()->where('current_status', 'canceled')->count();
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->count();
        $totalSessions = $cancelledSessions + $completedSessions;
        $productivityScore = round(($completedSessions / $totalSessions) * 100, 2);

        $allTimeStats = [
            'sessions_completed' => $completedSessions,
            'sessions_started' => $startedSessions,
            'hours_focused' => $hoursFocused,
            'productivity_score' => $productivityScore,
        ];

        return $allTimeStats;


    }

    public function weeklyStats() {

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek,$endOfWeek])->count();
        $startedSessions = $this->focusSessions()->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $hoursFocused = $this->focusSessions()->whereNotNull('session_length')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->sum('session_length');
        $cancelledSessions = $this->focusSessions()->where('current_status', 'canceled')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $completedSessions = $this->focusSessions()->where('current_status', 'ended')->whereBetween('started_at', [$startOfWeek, $endOfWeek])->count();
        $totalSessions = $cancelledSessions + $completedSessions;
        $productivityScore = round(($completedSessions / $totalSessions) * 100, 2);

        $weeklyStats = [
            'sessions_completed' => $completedSessions,
            'sessions_started' => $startedSessions,
            'hours_focused' => $hoursFocused,
            'productivity_score' => $productivityScore,
        ];

        return $weeklyStats;



    }

    public function resetAll() {
        $this->focusSessions()->delete();
        $this->breaks()->delete();
    }

    public function hoursByDay($labels)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $hoursByDay = $this->focusSessions()
            ->selectRaw('date(started_at) AS day_of_week, SUM(session_length)/60 AS total_hours')
            ->whereNotNull('completed_at') // Only consider completed sessions
            ->groupBy('day_of_week')
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->orderBy('day_of_week')
            ->get();
        $results = [];
        foreach ($hoursByDay as $hours) {
            $result = [];
            $day = Carbon::createFromFormat('Y-m-d', $hours->day_of_week);
            $result['day_of_week'] = $day->englishDayOfWeek;
            $result['total_hours'] = $hours->total_hours;
            $results[] = $result;
        }

        $chartData = [];
        $resultsByDay = collect($results);
        foreach ($labels as $label) {
            //Find the total for label
            $totalForThisDay = $resultsByDay->where('day_of_week', $label)->first();
            $chartData[] = $totalForThisDay ? $totalForThisDay['total_hours']  : 0;
        }

        return $chartData;
    }
}
