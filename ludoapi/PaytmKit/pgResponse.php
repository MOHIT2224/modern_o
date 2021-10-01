<?php
require('./lib/config.php');
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.



	if (isset($_POST) && count($_POST)>0 )
	{ 
		foreach($_POST as $paramName => $paramValue) {
			//	echo "<br/>" . $paramName . " = " . $paramValue;
		}
	}
	
	//die;

if($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
				
	}
	else {
		
		echo "<b>Transaction status is failure</b>" . "<br/>"; ?>
		
		<form method="post" name="redirect" action="http://gaminggaze.com/api/failed.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
 <?php
	}

	
	
	 
	
if ($_POST["STATUS"] == "TXN_SUCCESS") {
	$html['success'] = 1;
    $html['message'] = "Your payment was successful";
	$order_id = $_POST['ORDERID'];
	$trnsid = $_POST['TXNID'];
	$orderamount = $_POST['TXNAMOUNT'];
	$mobile = $_POST['mobile'];
	$emailid = $_POST['emailid'];
	
	$totald=mysqli_query($conn, "SELECT * FROM payment WHERE orderid='".$order_id."'");
 $totaldopsit = mysqli_fetch_assoc($totald);
 
   $userid=$totaldopsit['uid'];
	 
	 
	
	
	$sql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
	 
while($row = mysqli_fetch_assoc($sql)){ 

$newcoins = $row['coins']+ $orderamount; 
$bonus = $row['bonus']+ 10; 

}
$insertquery =mysqli_query($conn,"update users set coins='".$newcoins."', bonus='".$bonus."' WHERE id='".$userid."'");
	
	
$insertq =mysqli_query($conn,"update payment set status='Success', trnsid='".$trnsid."' WHERE uid='".$userid."' and orderid='".$order_id."' ");

$sqler=mysqli_query($conn, "SELECT * FROM payment WHERE uid='".$userid."' and status='Success'");
	$count = mysqli_num_rows($sqler);
	    if($count > 0){
			
			
			$sqls=mysqli_query($conn, "SELECT * FROM refered WHERE uid='".$userid."' and status='0'");
	$countss = mysqli_num_rows($sqls);
	    if($countss > 0){
			
			
		 $insert =mysqli_query($conn,"update refered set status='1' WHERE uid='".$userid."'");
$gatrcode = mysqli_fetch_assoc($sqls);		
	 $refercode = $gatrcode['rcode'] ;
		 
		 $gtrc=mysqli_query($conn, "SELECT * FROM users WHERE refercode='".$refercode."'");
		 
		
		$rowss = mysqli_fetch_assoc($gtrc);
		$bonus = $rowss['bonus'] + 15;		
		$insertquery =mysqli_query($conn,"update users set bonus='".$bonus."' WHERE refercode='".$refercode."'");
		
		
		
		
		$usersql=mysqli_query($conn, "SELECT * FROM users WHERE id='".$userid."'");
		$rowuser = mysqli_fetch_assoc($usersql);
		$bonusu = $rowuser['bonus'] + 30;
		
	   $insert =mysqli_query($conn,"update users set bonus='".$bonusu."', refer='".$refercode."' WHERE id='".$userid."'");
	   
		
		
		}
			
			
			
		}


   // $html = "<p>Your payment was successful</p><p>Payment ID: {$_POST['razorpay_payment_id']}</p>";
             
			// print_r($_POST);
		 

//$admin_message = "Your payment ".$orderamount." was successful, with order number ".$order_id." ";
//$admin_msg = urlencode($admin_message);	 

//$admin_url="http://sms.bhashsms.com/api/sendmsg.php?user=hyikegame&pass=123456&sender=HYIKEG&phone=$mobile&text=$admin_msg&priority=ndnd&stype=normal";
//$admin_url="http://5.9.0.178:8000/Sendsms?user=hyikefor&password=123456&sender=HYIKEG&dest=$mobile&apid=59523&text=$admin_msg&dcs=0";
//$admin_url="http://message.mrads.in/api/sendmsg.php?user=shakthitech&pass=123456&sender=SHAKTI&phone=$mobile&text=$admin_msg&priority=ndnd&stype=normal";
//if($notp!='0'){
//$admin_url="http://sms.smsmenow.in/sendsms.jsp?user=autows&password=3494dac476XX&mobiles=$mobile&sms=$admin_msg&senderid=AUTOWS";
//$result = file($admin_url);
			?> 
			<form method="post" name="redirect" action="http://gaminggaze.com/api/success.php"> </form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
<?php 	}
else
{
	    $html['success'] = 0;
		$html['message'] = $error; ?>		
		<form method="post" name="redirect" action="http://gaminggaze.com/api/failed.php">

		</form>
		</center>
		<script language='javascript'>document.redirect.submit();</script>
 <?php
  //  $html = "<p>Your payment failed</p><p>{$error}</p>";        
}

//echo $html;
$json = json_encode($html);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>