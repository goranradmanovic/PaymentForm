<?php

  require_once 'app/start.php';

  use Antavo\Core\ValidationCore;
  use Antavo\Core\CurrencyConvertCore;

  //If form is subbmited
  if (isset($_POST['submit-btn']))
  {
    $validation = new ValidationCore($_POST);

    $errors = $validation->validateForm();

    //If errors array is empty, no errors
    if (empty($errors))
    {
      $currencyConverter = new CurrencyConvertCore;

      $convertedCurrency = $currencyConverter->convertCurrency($_POST['amount']);
    }
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antavo Payment Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  </head>
  <body>
    <div class="contatiner">
      <div class="row">
        <div class="col-md-6 offset-md-3 mt-5 pt-5">

          <?php if (isset($convertedCurrency)) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Successful conversion!</strong> *** <?php echo "{$_POST['amount']} HUF = {$convertedCurrency} EUR"?> ***
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <div class="card">
            <h5 class="card-header">Payment Form</h5>
            <div class="card-body">
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                <div class="form-group">
                  <label for="exampleFormControlInput1">Credit Card Number</label>
                  <input type="text" name="card-number"  class="form-control" pattern="\d*" required maxlength="16" minlength="16" class="form-control" id="exampleFormControlInput1" value="<?php echo (isset($_POST['card-number'])) ? htmlspecialchars($_POST['card-number']) : ''; ?>"  placeholder="Enter 16 digits credit card number">
                  <div class="invalid-feedback" style="display: <?php echo $errors['card-number-valid'] ? 'block' : 'none'; ?>">
                    <?php echo $errors['card-number-valid'] ?? '' ?>
                  </div>
                  <div class="invalid-feedback" style="display: <?php echo $errors['card-number-check'] ? 'block' : 'none'; ?>">
                    <?php echo $errors['card-number-check'] ?? '' ?>
                  </div>
                </div>

                <div class="form-group w-50">
                  <label for="inlineDatebox1">Card Expiration Mounth & Year</label>
                  <input class="form-control" name="mounth-year" type="month" min="2017-01" max="2030-01" value="<?php echo (isset($_POST['mounth-year'])) ? htmlspecialchars($_POST['mounth-year']) : ''; ?>" required id="inlineDatebox1">
                  <div class="invalid-feedback" style="display: <?php echo $errors['mounth-year'] ? 'block' : 'none'; ?>">
                    <?php echo $errors['mounth-year'] ?? '' ?>
                  </div>
                </div>

                <div class="form-group">
                  <label for="exampleFormControlInput2">Amount to pay (HUF)</label>
                  <input type="text" name="amount" pattern="\d*" class="form-control" value="<?php echo (isset($_POST['amount'])) ? htmlspecialchars($_POST['amount']) : ''; ?>" id="exampleFormControlInput2" required maxlength="1000000" minlength="1" placeholder="Enter the amount you want to pay with no decimals">
                  <div class="invalid-feedback" style="display: <?php echo $errors['amount'] ? 'block' : 'none'; ?>">
                    <?php echo $errors['amount'] ?? '' ?>
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" name="submit-btn" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
