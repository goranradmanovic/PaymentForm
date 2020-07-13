<?php

namespace Antavo\Core;

class ValidationCore {

  private $data; //POST data
  private $errors = []; //errors array
  private static $fields = ['card-number', 'mounth-year', 'amount']; //form fields

  public function __construct($postData)
  {
    $this->data = $postData; //Set the POST data
  }

  //Check if form fileds exists in incoming POST data
  public function validateForm()
  {
    //Check if keys exists the data from front end
    foreach(self::$fields as $field)
    {
      if (!array_key_exists($field, $this->data))
      {
        trigger_error("$field is not present in data!");
        return;
      }
    }

    //Check for errors
    $this->validateCardNumberValue();
    $this->validateCardExpirationDate($this->data['mounth-year']);
    $this->validateAmount();
    $this->luhnCheck(intval($this->data['card-number']));

    return $this->errors; //Return errors if there is any
  }

  //Check if card number is valid number and in radnge of 16
  private function validateCardNumberValue()
  {
    $val = trim($this->data['card-number']); //Trim the value

    //Check if value is empty
    if (empty($val))
    {
      $this->addError('card-number-valid', 'Card number cannot be empty and is required.');
    }
    else
    {
      //Check if value is numeric and in lenght of 16 chars.
      if ((bool) !preg_match('/^\d{16,16}$/', $val))
      {
        $this->addError('card-number-valid', 'Card number must be 16 chars and numeric only.');
      }
    }
  }

  //Check if card expired date is valid
  private function validateCardExpirationDate($selectedDate)
  {
    $expires = strtotime($selectedDate);
    $now = strtotime('now');

    //If current date is bigger then expired date
    if ($expires <= $now)
    {
      $this->addError('mounth-year', 'Card card date has expire.');
    }
  }

  private function validateAmount()
  {
    $val = intval(trim($this->data['amount'])); //Trim the value and convert to integer

    $filterOptions = [
      'options' => [
        'min_range' => 1,
        'max_range' => 1000000
      ]
    ];

    //Check if value is empty
    if (empty($val))
    {
      $this->addError('amount', 'Amount to pay cannot be empty and is required.');
    }
    else
    {
      //Check if value is number in range 1-1000000
      if (filter_var($val, FILTER_VALIDATE_INT, $filterOptions) === false)
      {
        $this->addError('amount', 'Amount to pay must be 1-1000000 chars and numeric only.');
      }
    }
  }

  //Add error to errors array
  private function addError($key, $val)
  {
    $this->errors[$key] = $val;
  }

  /*
    !!!!COPIED FROM INTERENT AND ADDED SMALL MODIFICATION!!!!
    Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
    This code has been released into the public domain, however please
    give credit to the original author where possible.
  */

  private function luhnCheck($number)
  {
      // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
      $number = preg_replace('/\D/', '', $number);

      // Set the string length and parity
      $number_length = strlen($number);
      $parity = $number_length % 2;

      // Loop through each digit and do the maths
      $total = 0;

      for ($i = 0; $i < $number_length; $i++) {
        $digit = $number[$i];
        // Multiply alternate digits by two
        if ($i % 2 == $parity) {
          $digit *= 2;
          // If the sum is two digits, add them together (in effect)
          if ($digit > 9) {
            $digit -= 9;
          }
        }
        // Total up the digits
        $total += $digit;
      }

      // If the total mod 10 equals 0, the number is valid
      if (!($total % 10 == 0))
      {
        $this->addError('card-number-check', 'Card number is not valid.');
      }
  }
}
