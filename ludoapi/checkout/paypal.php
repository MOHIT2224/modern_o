<!--<form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="hyikeforum@gmail.com">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="item_name" value="HYIKE Balance">
<input type="hidden" name="amount" value="12.99">
<input type="submit" class="razorpay-payment-button" value="Pay with Paypal" border="0" name="submit">
</form>

<h2>---- PAY</h2>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="5BRZLU6LP496S">
<input type="hidden" name="currency_code" value="INR">
<input type="hidden" name="amount" value="3500">
<input type="submit" class="razorpay-payment-button" value="Pay with Paypal" border="0" name="submit">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form><form name="_xclick" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">

 <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="5BRZLU6LP496S">
<input type="hidden" name="amount" value="<?php // echo $data['orderamount']?>" />
<input type="hidden" value="<?php // echo $json;?>" name="custom" maxlength="200">
<input type="submit" class="razorpay-payment-button" value="Pay with Paypal" border="0" name="submit">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>

 -->
 
 
 
  <form class="paypal" action="payments.php" method="post" id="paypal_form">
        <input type="hidden" name="cmd" value="_xclick" />
        <input type="hidden" name="no_note" value="1" />
        <input type="hidden" name="lc" value="UK" />
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
        <input type="hidden" name="first_name" value="<?php echo $_GET['name']; ?>" />
        <input type="hidden" name="last_name" value="<?php echo $_GET['name']; ?>" />
		<input type="hidden" name="amount" value="<?php echo $data['orderamount']?>" />
        <input type="hidden" name="payer_email" value="<?php echo $data['emailid']?>" />
		 <input type="hidden" name="orderid" value="<?php echo $data['horderid']?>" />
        <input type="hidden" name="item_number" value="2" / >
		<input type="hidden" name="userid" value="<?php echo $data['userid']?>" / >
		<input type="hidden" value="<?php echo $data['horderid']?>" name="custom"  / >
       <input type="submit" class="razorpay-payment-button" value="Pay with Paypal" border="0" name="submit">
    </form>
  