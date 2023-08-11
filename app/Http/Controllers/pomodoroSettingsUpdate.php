<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pomodoroSettingsUpdate extends Controller
{
    /**
     * Display the view settings update
     *
     * This method is used to render the 'settings' view that allows users to change settings
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('settings');
    }


}
