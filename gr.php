<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" >
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
      Hosted Checkout: Sample PHP Payment Form
    </title>
<style type="text/css">
  label {
      display: block;
      margin: 5px 0px;
      color: #AAA;
   }
   input {
      display: block;
   }
   input[type=submit] {
      margin-top: 20px;
   }
</style>

  </head>
  <body>
  <h1>
    Hosted Checkout: Sample PHP Payment Form
  </h1>

 
  <form action="https://checkout.e-xact.com/pay" method="POST">

<?php
      $x_login = "WSP-#####-##";  //  Take from Payment Page ID in Payment Pages interface
      $transaction_key = "#############"; // Take from Payment Pages configuration interface
      $x_amount = "595.99";
      $x_currency_code = "CAD"; // Needs to agree with the currency of the payment page
      srand(time()); // initialize random generator for x_fp_sequence
      $x_fp_sequence = rand(1000, 100000) + 123456;
      $x_fp_timestamp = time(); // needs to be in UTC. Make sure webserver produces UTC

      // The values that contribute to x_fp_hash 
      $hmac_data = $x_login . "^" . $x_fp_sequence . "^" . $x_fp_timestamp . "^" . $x_amount . "^" . $x_currency_code;
      $x_fp_hash = hash_hmac('MD5', $hmac_data, $transaction_key);
      
      echo ('<label>x_login</label><input name="x_login" value="' . $x_login . '">' );
      echo ('<label>x_amount</label><input name="x_amount" value="' . $x_amount . '">' );
      echo ('<label>x_fp_sequence</label><input name="x_fp_sequence" value="' . $x_fp_sequence . '">' );
      echo ('<label>x_fp_timestamp</label><input name="x_fp_timestamp" value="' . $x_fp_timestamp . '">' );
      echo ('<label>x_fp_hash</label><input name="x_fp_hash" value="' . $x_fp_hash . '" size="50">' );
      echo ('<label>x_currency_code</label><input name="x_currency_code" value="' . $x_currency_code . '">');
?>

      <input type="hidden" name="x_show_form" value="PAYMENT_FORM"/>
      <input type="hidden" name="x_test_request" value="TRUE"/>

      <input type="submit" value="Pay with Hosted Checkout"/>
    </form>

  </body>
</html>