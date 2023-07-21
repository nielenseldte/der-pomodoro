<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pomodoroSettingsUpdate extends Controller
{
    public function index()
    {
        return view('settings');
    }

    
}
