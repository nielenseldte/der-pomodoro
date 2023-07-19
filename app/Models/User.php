<?php

namespace App\Models;

use App\Models\Settings;
use App\Models\FocusSession;
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

    public function isOnBreak()
    {
        return session()->get('break', false);
    }

    public function startBreak()
    {
        session()->put('break', true);
        UserBreak::start($this);
    }

    public function endBreak()
    {
        session()->put('break', false);
    }

    public function getSettings()
    {
        $focusLength = $this->settings->session_length;
        $shortBreakLength = $this->settings->short_break_length;
        $longBreakLength = $this->settings->long_break_length;
        $longBreakInterval = $this->settings->long_break_interval;
        $dailyGoal = $this->settings->daily_goal;

        return [
            'focusLength' => $focusLength,
            'shortBreakLength' => $shortBreakLength,
            'longBreakLength' => $longBreakLength,
            'longBreakInterval' => $longBreakInterval,
            'dailyGoal' => $dailyGoal,
        ];
    }
}
