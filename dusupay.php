<?php
error_reporting(0);

// Get value from payment Ajax
$currency = $_POST['currency'];
$amount = $_POST['amount'];
$type = $_POST['type'];
$typeMM = $_POST['typeMM'];
$accNo = $_POST['accNo'];
$refrence = $_POST['refrence'];
$subject = $_POST['subject'];

if ($subject == "accNumbers") {

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.dusupay.com/v1/payment-options/COLLECTION/mobile_money/'.$currency.'?api_key=PUBK-2021ffbe5e048c961bd68da4f76e95efd',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'secret-key: SECK-2022548b38bfc58ab95e964252c35d49d',
      'Content-Type: application/json'
    ),
  ));
  $res = curl_exec($curl);
  curl_close($curl);
  $response = json_decode($res, true);

  // echo $res.'<br>';
  $encode = $response['data'];
  echo json_encode($encode, true);

} else if ($subject == "payment") {

  if ($type == "CARD"  && ($currency == "NGN" || $currency == "UGX" || $currency == "KES" || $currency == "TZS" || $currency == "GHS" || $currency == "USD" || $currency == "GBP" || $currency == "EUR")) {

    if ($currency == "NGN") {

      $providerid = "local_ngn";

    } elseif ($currency == "UGX") {

      $providerid = "international_ugx";

    } elseif ($currency == "KES") {

      $providerid = "international_kes";

    } elseif ($currency == "TZS") {

      $providerid = "international_tzs";

    } elseif ($currency == "GHS") {

      $providerid = "international_ghs";

    } elseif ($currency == "USD") {

      $providerid = "international_usd";

    } elseif ($currency == "GBP") {

      $providerid = "international_gbp";

    } elseif ($currency == "EUR") {

      $providerid = "international_eur";

    }

  } elseif ($type == "BANK" && ($currency == "NGN" || $currency == "ZAR")) {

    if ($currency == "NGN") {

      $providerid = "bank_ng";

    } elseif ($currency == "ZAR") {

      $providerid = "bank_za";

    }

  } elseif ($type == "MOBILE_MONEY"  && ($currency == "UGX" || $currency == "KES" || $currency == "TZS" || $currency == "RWF" || $currency == "BIF" || $currency == "GHS" || $currency == "XAF" || $currency == "ZMW")) {

    if ($currency == "UGX") {

      $providerid = $typeMM."_ug";

    } elseif ($currency == "KES") {

      $providerid = $typeMM."_ke";

    } elseif ($currency == "TZS") {

      $providerid = $typeMM."_tz";

    } elseif ($currency == "RWF") {

      $providerid = $typeMM."_rw";

    } elseif ($currency == "BIF") {

      $providerid = $typeMM."_bi";

    } elseif ($currency == "GHS") {

      $providerid = $typeMM."_gh";

    } elseif ($currency == "XAF") {

      $providerid = $typeMM."_cm";

    } elseif ($currency == "ZMW") {

      $providerid = $typeMM."_zm";

    }

  }

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.dusupay.com/v1/collections',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{ 
      "api_key": "PUBK-2021ffbe5e048c961bd68da4f76e95efd", 
      "currency": "'.$currency.'", 
      "amount": '.$amount.',
      "method": "'.$type.'", 
      "provider_id": "'.$providerid.'",
      "account_number": "'.$accNo.'",
      "merchant_reference": "'.$refrence.'", 
      "narration": "Short Payment reason",
      "redirect_url": "fb.com"
    }',
    CURLOPT_HTTPHEADER => array(
      'secret-key: SECK-2022548b38bfc58ab95e964252c35d49d',
      'Content-Type: application/json'
    ),
  ));
  $res = curl_exec($curl);
  curl_close($curl);
  $response = json_decode($res, true);

  // echo $res.'<br>';

  if ($response['message'] == "Transaction Initiated" && ($type == "CARD" || $type == "BANK")) {

    echo $response['data']['payment_url'];

  } else if ($response['message'] == "Transaction Initiated" && $type == "MOBILE_MONEY") {

    echo "Transaction Completed Successfully";

  } else {

    echo $response['status']."!!! ".$response['message'].".";

  }

}
