<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QuoteService;
use Artisan;

class StatsController extends Controller
{
    /**
     * Fetches an inspirational quote from the quote service service and returns it to the view
     *
     * also renders the stats view with the $quote passed
     *
     * @param \App\Services\QuoteService $quoteService The QuoteService Service used to fetch quotes from external API.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(QuoteService $quoteService)
    {
        $quote = $quoteService->getInspirationalQuote();

        return view('stats', compact('quote')); //The view() function is used to render the welcome.blade.php view, passing the $quote variable to it using the compact() function. This makes the $quote variable available in the view.
    }


}
