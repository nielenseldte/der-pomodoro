<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function user() {
        $this->belongsTo(User::class);

    }

    public static function createSettings(User $user = null) {
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

    public function updateSettings() {

    }
}
