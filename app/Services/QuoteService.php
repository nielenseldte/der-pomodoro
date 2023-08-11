<?php

namespace App\Services;

use GuzzleHttp\Client;

class QuoteService
{
    /**
     * getInspirationalQuote() fetches an inspirational quote in JSON format from the forismatic API
     *
     * we use jsondecode to convert the json to an array and if the $quoteData is not empty we pass its value into an associative array
     * When the api failed to respond with a quote a generic quote is returned to prevent errors
     * @return array An associative array with the fetched quote's text and author.
     */
    public function getInspirationalQuote()
    {
        $client = new Client(); // create an instance of the HTTP client
        $response = $client->get('http://api.forismatic.com/api/1.0/?method=getQuote&format=json&lang=en'); //send a get to api
        $quoteData = json_decode($response->getBody(), true); //json is decoded into an associative array


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
