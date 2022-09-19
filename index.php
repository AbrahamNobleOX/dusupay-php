<?php

include('db.php');

// Get value from payment Ajax
$id = $_GET['id'];

// Check database for price
$pre_stmt = $conn->prepare("select price from property where id = '$id'");
$pre_stmt->bind_param();
$pre_stmt->execute();
$result = $pre_stmt->get_result();
$row = $result->fetch_assoc();
$price = $row['price'];

?>

<!DOCTYPE html>
<html lang="en">
 <head>
   <title>Payment Page</title>
   <meta name="viewport" content="width=device-width, initial-scale=1" />

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

 </head>
 <body>
  <div class="container">
    <span>Price: <?php echo $price; ?></span>

    <br>

    <p>Get Mobile Money Test Account Numbers</p>
    <select id="currencyAC" required>
      <option>select currency...</option>
      <option value="UG">Uganda</option>
      <option value="KE">Kenya</option>
      <option value="TZ">Tanzania</option>
      <option value="RW">Rwanda</option>
      <option value="BI">Burundi</option>
      <option value="GH">Ghana</option>
      <option value="CM">Cameroon</option>
      <option value="ZM">Zambia</option>
    </select>

    <button type="button" id="paybuttonAC" onclick="dusupayAC()" >Generate</button>
    
    <p id="details"></p>
    
    <hr>

    <p>Make Payment</p>
    <select id="currency" onchange="onChangeSelect()" required>
      <option>select currency...</option>
      <option value="NGN">Nigerian Naira</option>
      <option value="UGX">Uganda</option>
      <option value="KES">Kenya</option>
      <option value="TZS">Tanzania</option>
      <option value="RWF">Rwanda</option>
      <option value="BIF">Burundi</option>
      <option value="GHS">Ghana</option>
      <option value="XAF">Cameroon</option>
      <option value="ZAR">South Africa</option>
      <option value="ZMW">Zambia</option>
      <option value="USD">US Dollars</option>
      <option value="GBP">British Pounds</option>
      <option value="EUR">Euro</option>
    </select>

    <select id="type" onchange="onChangeSelectMM()" required>
      <option value="" >select type...</option>
      <option value="CARD" id="card">Card</option>
      <option value="BANK" id="bank">Bank</option>
      <option value="MOBILE_MONEY" id="MM">Mobile Money</option>
    </select>

    <select id="typeMM">
      <option value="" >select Mobile Money provider...</option>
      <option value="mpesa" id="mpesa">Mpesa</option>
      <option value="mtn" id="mtn">MTN</option>
      <option value="airtel" id="airtel">Airtel</option>
      <option value="equitel" id="equitel">Equitel</option>
      <option value="tigo" id="tigo">Tigo</option>
      <option value="vodacom" id="vodacom">Vodacom</option>
      <option value="vodafone" id="vodafone">Vodafone</option>
      <option value="econet" id="econet">Econet</option>
      <option value="orange" id="orange">Orange</option>
      <option value="zamtel" id="zamtel">Zamtel</option>
    </select>

    <input type="text" id="accNo" placeholder="Account No" />

    <button type="button" id="paybutton" onclick="dusupay()" >Pay</button>
  </div>
 </body>

 <script type="text/javascript">

  function dusupayAC() {

    var currency = document.getElementById('currencyAC').value;

    $.ajax({
      url:"./dusupay.php",  
      method:"POST",
      data:{
        currency: currency,
        subject: "accNumbers"
      },
      success:function(data) {

          $('#details').html(data);
        
      }
    });
  }
 
  $('#typeMM').hide();
  $('#accNo').hide();

  function onChangeSelect() {

    var currency = document.getElementById('currency').value;
    $('#type').val("");

    if (currency == "NGN") {
      $('#card').show();
      $('#bank').show();
      $('#MM').hide();
      $('#typeMM').hide();
      $('#accNo').hide();
    } else if (currency == "ZAR") {
      $('#card').hide();
      $('#bank').show();
      $('#MM').hide();
      $('#typeMM').hide();
      $('#accNo').hide();
    } else if (currency == "RWF" || currency == "BIF" || currency == "XAF" || currency == "ZMW") {
      $('#card').hide();
      $('#bank').hide();
      $('#MM').show();
      $('#typeMM').show();
    } else if (currency == "USD" || currency == "GBP" || currency == "EUR") {
      $('#card').show();
      $('#bank').hide();
      $('#MM').hide();
      $('#typeMM').hide();
      $('#accNo').hide();
    } else {
      $('#card').show();
      $('#bank').hide();
      $('#MM').show();
      $('#accNo').hide();
    }

  }

  function onChangeSelectMM() {

    var type = document.getElementById('type').value;

    if (type == "MOBILE_MONEY") {
      $('#typeMM').show();
      $('#accNo').show();
    } else {
      $('#typeMM').hide();
      $('#accNo').hide();
    }

  }
  
  function dusupay() {

    var currency = document.getElementById('currency').value;
    var amount =  "<?php echo $price; ?>";
    var type = document.getElementById('type').value;
    var typeMM = document.getElementById('typeMM').value;
    var accNo = document.getElementById('accNo').value;
    var refrence = ""+Math.floor((Math.random() * 1000000000) + 1);

    if (currency == "" || type == "" || amount == "") {
      
      alert("All field is required.");
      
    } else {

      $.ajax({
        url:"./dusupay.php",  
        method:"POST",
        data:{
          currency: currency,
          amount: amount,
          type: type,
          typeMM: typeMM,
          accNo: accNo,
          refrence: refrence,
          subject: "payment"
        },
        success:function(data) {
          if (data.includes('https')) {
            window.location.href = data;
          } else {
            alert(data);
          }
        }
      });
    }
  }
</script>
</html>