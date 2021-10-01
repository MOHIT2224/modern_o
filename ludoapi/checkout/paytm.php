<!DOCTYPE html>
<html>
<head>
  <title>Cashfree - Signature Generator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body onload="document.frm1.submit()">
    
<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->

<form  name="frm1" action="PaytmKit/pgRedirect.php" method="POST">
 
  <input type="hidden" id="CUST_ID" name="CUST_ID" value="<?php echo $data['userid']?>">
					<input type="hidden" id="INDUSTRY_TYPE_ID" name="INDUSTRY_TYPE_ID" value="Retail">
					<input type="hidden"  id="CHANNEL_ID" name="CHANNEL_ID" value="WEB">
  
   <input type="text" type="hidden" class="form-control" id="ORDER_ID" name="ORDER_ID" size="20" maxlength="20" autocomplete="off" 

tabindex="1" value="<?php echo $data['horderid']?>">
  
  <input  type="hidden" type="text" class="form-control" id="TXN_AMOUNT" name="TXN_AMOUNT" autocomplete="off" tabindex="5" 

value="<?php echo $data['orderamount']?>">
  
  
  
</form>

 <script>
   // window.onload = function() {
    <!-- Deep link URL for existing users with app already installed on their device -->
       // window.location = 'yourapp://app.com/?screen=xxxxx';
    <!-- Download URL (TUNE link) for new users to download the app -->
        //setTimeout("window.location = 'http://hastrk.com/serve?action=click&publisher_id=1&site_id=2';", 1000);
  //  }
    </script>
