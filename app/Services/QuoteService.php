<?php

namespace App\Services;

use GuzzleHttp\Client;

class QuoteService
{
    //method for fetching random quote from forismatic api
    public function getInspirationalQuote()
    {
        $client = new Client();
        $response = $client->get('http://api.forismatic.com/api/1.0/?method=getQuote&format=json&lang=en');
        $quoteData = json_decode($response->getBody(), true);


        if (isset($quoteData['quoteText']) && isset($quoteData['quoteAuthor'])) {
            return [
                'text' => $quoteData['quoteText'],
                'author' => $quoteData['quoteAuthor'],
            ];
        } else {
            return [
                'text' => 'Success is not final; failure is not fatal: It is the courage to continue that counts.',
                'author' => 'Winston S. Churchill',
            ];
        }
    }
}
