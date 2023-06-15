<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuoteService;

class DashboardController extends Controller
{
    public function index(QuoteService $quoteService)
    {
        $quote = $quoteService->getInspirationalQuote();

        return view('dashboard', compact('quote')); //The view() function is used to render the welcome.blade.php view, passing the $quote variable to it using the compact() function. This makes the $quote variable available in the view.
    }
}
