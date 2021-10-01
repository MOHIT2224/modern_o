
    
<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->

<form action="verify.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="<?php echo $data['horderid']?>"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="shopping_order_id" value="<?php echo $data['horderid']?>">
  <input type="hidden" name="userid" value="<?php echo $data['userid']?>"> 
  <input type="hidden" name="orderamount" value="<?php echo $data['orderamount']?>">
  <input type="hidden" name="mobile" value="<?php echo $data['mobile']?>">
  <input type="hidden" name="emailid" value="<?php echo $data['emailid']?>">
  
</form>

 <script>
   // window.onload = function() {
    <!-- Deep link URL for existing users with app already installed on their device -->
       // window.location = 'yourapp://app.com/?screen=xxxxx';
    <!-- Download URL (TUNE link) for new users to download the app -->
        //setTimeout("window.location = 'http://hastrk.com/serve?action=click&publisher_id=1&site_id=2';", 1000);
  //  }
    </script>
