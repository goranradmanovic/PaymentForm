<?php

namespace Antavo\Core;

class CurrencyConvertCore
{
  private $apiURL = 'https://api.exchangeratesapi.io/latest?base=HUF';

  public function __construct()
  {

  }

  //Function for converting currency from HUF to EUR
  public function convertCurrency($amount)
  {
    $handle = curl_init(); //Start the cURL

    // Set the url
    curl_setopt($handle, CURLOPT_URL, $this->apiURL);

    // Set the result output to be a string.
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    //Get the data
    $output = curl_exec($handle);

    //Convert to stdClass or array
    $responseData = json_decode($output);

    //Close cURL
    curl_close($handle);

    //Calculate HUF to EUR
    $total = $responseData->rates->EUR * $amount;

    //Return and format result
    return number_format($total, 2, '.', '');
  }
}
