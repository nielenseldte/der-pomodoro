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

    public function hasActiveBreak() {
        $this->load('breaks');
        return ($this->breaks->whereIn('current_status',[UserBreak::STATUS_TICKING, UserBreak::STATUS_PAUSED])->first() !== null);
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

    public function dailyProgress() {

        $dailyGoal = $this->settings->daily_goal;
        $hoursCompleted = ($this->focusSessions()->whereDate('completed_at',Carbon::today())->sum('session_length'))/60;
        $progress = ($hoursCompleted/$dailyGoal) * 100;
        return $progress;
    }

}
